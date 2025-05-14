<?php
namespace App\Livewire;

use App\Models\Commande;
use App\Models\ModePaiement;
use App\Models\SousFamille;
use App\Models\Famille;
use App\Models\Article;
use Livewire\Component;
use App\Models\Table;
use App\Models\Etat;
use App\Models\Type;
use App\Models\Message;
use App\Models\DetailCommande;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Main extends Component
{
    // Properties with proper typing for better IDE support
    public $familles = [];
    public $tables = [];
    public $sousFamilles = [];
    public $articles = [];
    public $messages = [];
    public $selectedTable = null;
    public $selectedType = null;
    public $selectedSousFamille = null;
    public $etat = [];
    public $selectedEtat = null;
    public $showModal = false;
    public $selectedModePaiement = null;
    public $goodToSet = null;
    public $activeFamille = null;
    public $type = [];
    public $modeDepaiment = [];
    public $panier = [];
    public $observation = null;
    public $total = 0;
    public $sousTotal = 0;
    public $search = null;

    // Success/error message properties
    public $successMessage = null;
    public $errorMessage = null;

    // Lifecycle hook - initialize component data
    public function mount()
    {
        try {
            $this->messages = Message::all();
            $this->familles = Famille::all();
            $this->etat = Etat::all();
            $this->type = Type::all();
            $this->modeDepaiment = ModePaiement::all();

            $this->tables = Table::with([
                'latestCommande' => fn($query) => $query->with('etat')
            ])->get();

            // Set initial famille
            if (!empty($this->familles)) {
                $this->activeFamille = $this->familles[0]['id'];
                $this->loadSousFamilles($this->activeFamille);
            }

            DB::table('commandes')
                ->where('etat_id', 3)
                ->update(['etat_id' => 2]);

        } catch (\Exception $e) {
            Log::error('Error in Main component mount: ' . $e->getMessage());
            $this->errorMessage = 'Failed to load POS data: ' . $e->getMessage();
        }
    }
    public function updatedActiveFamille($value)
    {
        if ($value) {
            $this->loadSousFamilles($value);
        }
    }

    public function updatedSelectedSousFamille($value)
    {
        if ($value) {
            $this->loadArticles($value);
        }
    }

    public function selectType($typeId)
    {
        $this->selectedType = $typeId;
    }

    public function loadSousFamilles($familleId)
    {
        try {
            $this->selectedSousFamille = null;
            $this->articles = [];
            $this->sousFamilles = SousFamille::where('famille_id', $familleId)->get();

            if (!empty($this->sousFamilles)) {
                $this->selectedSousFamille = $this->sousFamilles[0]['id'];
                $this->loadArticles($this->selectedSousFamille);
            }
        } catch (\Exception $e) {
            Log::error('Error loading sous-familles: ' . $e->getMessage());
            $this->errorMessage = 'Failed to load sous-familles: ' . $e->getMessage();
        }
    }


    public function loadArticles($sousFamilleId)
    {
        try {
            $this->selectedSousFamille = $sousFamilleId;

            $query = Article::where('sous_famille_id', $sousFamilleId)->get();

            // Apply search filter if present
            if ($this->search) {
                $query->where('designation', 'like', '%' . $this->search . '%');
            }

            $this->articles = $query;
        } catch (\Exception $e) {
            Log::error('Error loading articles: ' . $e->getMessage());
            $this->errorMessage = 'Failed to load menu items: ' . $e->getMessage();
        }
    }


    public function loadType($type)
    {
        $this->selectedType = $type;
    }


    public function addToPanier($articleId)
    {
        try {
            DB::beginTransaction();

            $article = Article::findOrFail($articleId);

            // Check stock availability before adding
            if ($article->stock < 1) {
                throw new \Exception('Item is out of stock');
            }

            if (isset($this->panier[$articleId])) {
                // Update existing item in cart
                $this->panier[$articleId]['quantity']++;
                $this->sousTotal += $article->prix_ht;
                $this->total += $article->prix_ht * (1 + $article->tva / 100);
            } else {
                // Add new item to cart
                $this->panier[$articleId] = [
                    'id' => $articleId,
                    'designation' => $article->designation,
                    'prix_ht' => $article->prix_ht,
                    'tva' => $article->tva,
                    'quantity' => 1,
                    'image' => $article->image,
                    'message' => null,
                    'stock' => $article->stock
                ];
                $this->sousTotal += $article->prix_ht;
                $this->total += $article->prix_ht * (1 + $article->tva / 100);
            }

            // Update stock in database
            DB::table('articles')->where('id', $articleId)->decrement('stock', 1);

            DB::commit();

            // Refresh articles display to show updated stock
            $this->loadArticles($this->selectedSousFamille);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding to cart: ' . $e->getMessage());
            $this->errorMessage = $e->getMessage();
        }
    }


    public function removeFromPanier($articleId)
    {
        try {
            DB::beginTransaction();

            if (isset($this->panier[$articleId])) {
                // Return the items to stock
                DB::table('articles')
                    ->where('id', $articleId)
                    ->increment('stock', $this->panier[$articleId]['quantity']);

                // Update totals
                $this->sousTotal -= $this->panier[$articleId]['prix_ht'] * $this->panier[$articleId]['quantity'];
                $this->total -= ($this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100)) * $this->panier[$articleId]['quantity'];

                // Remove from cart
                unset($this->panier[$articleId]);

                DB::commit();

                // Refresh articles display to show updated stock
                $this->loadArticles($this->selectedSousFamille);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing from cart: ' . $e->getMessage());
            $this->errorMessage = $e->getMessage();
        }
    }


    public function incOrDecQTE($articleId, $operation)
    {
        try {
            DB::beginTransaction();

            if ($operation === 'inc') {
                // Check stock availability before incrementing
                $article = DB::table('articles')->find($articleId);
                if ($article->stock < 1) {
                    throw new \Exception('No more stock available for this item');
                }

                $this->panier[$articleId]['quantity']++;
                DB::table('articles')->where('id', $articleId)->decrement('stock', 1);
                $this->sousTotal += $this->panier[$articleId]['prix_ht'];
                $this->total += $this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100);
            } elseif ($operation === 'dec') {
                if ($this->panier[$articleId]['quantity'] == 1) {
                    $this->removeFromPanier($articleId);
                    return;
                }

                $this->panier[$articleId]['quantity']--;
                DB::table('articles')->where('id', $articleId)->increment('stock', 1);
                $this->sousTotal -= $this->panier[$articleId]['prix_ht'];
                $this->total -= $this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100);
            }

            DB::commit();

            // Refresh articles display to show updated stock
            $this->loadArticles($this->selectedSousFamille);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating quantity: ' . $e->getMessage());
            $this->errorMessage = $e->getMessage();
        }
    }


    public function updateQuantity($articleId, $quantity)
    {
        if (!is_numeric($quantity) || !isset($this->panier[$articleId])) {
            return;
        }

        $quantity = (int) $quantity;
        $currentQuantity = $this->panier[$articleId]['quantity'];

        if ($quantity < 1) {
            $this->removeFromPanier($articleId);
            return;
        }

        try {
            DB::beginTransaction();

            if ($currentQuantity < $quantity) {
                // Increasing quantity - check stock
                $difference = $quantity - $currentQuantity;
                $article = DB::table('articles')->find($articleId);
                if ($article->stock < $difference) {
                    throw new \Exception("Only {$article->stock} items available in stock");
                }

                DB::table('articles')
                    ->where('id', $articleId)
                    ->decrement('stock', $difference);

                $this->sousTotal += $this->panier[$articleId]['prix_ht'] * $difference;
                $this->total += $this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100) * $difference;
            } elseif ($currentQuantity > $quantity) {
                // Decreasing quantity - return to stock
                $difference = $currentQuantity - $quantity;

                DB::table('articles')
                    ->where('id', $articleId)
                    ->increment('stock', $difference);

                $this->sousTotal -= $this->panier[$articleId]['prix_ht'] * $difference;
                $this->total -= $this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100) * $difference;
            }

            if ($currentQuantity != $quantity) {
                $this->panier[$articleId]['quantity'] = $quantity;
            }

            DB::commit();

            // Refresh articles to show updated stock
            $this->loadArticles($this->selectedSousFamille);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating quantity: ' . $e->getMessage());
            $this->errorMessage = $e->getMessage();
        }
    }


    public function processPayment()
    {
        if (empty($this->panier)) {
            $this->errorMessage = 'Cannot proceed with an empty cart';
            return;
        }

        if (!$this->selectedTable && $this->selectedType != 1) {
            $this->errorMessage = 'Please select a table before proceeding to payment';
            return;
        }

        $this->showModal = true;
    }

    public function confirmPayment()
    {
        try {
            // Different validation rules based on order type
            if ($this->selectedType != 1) { // For dine-in orders
                $this->validate([
                    'selectedTable' => 'required|exists:tables,id',
                    'selectedType' => 'required|exists:types,id',
                    'selectedModePaiement' => 'required|exists:mode_paiements,id',
                ]);
            } 

            if (empty($this->panier)) {
                throw new \Exception('Cart is empty');
            }

            DB::transaction(function () {
                // Create new order record
                $commandeId = DB::table('commandes')->insertGetId([
                    'date' => now()->format('Y-m-d'),
                    'heure' => now()->format('H:i:s'),
                    'etat_id' => $this->selectedType == 1 ? Etat::TEMPORARY : Etat::CONFIRMED,
                    'type_id' => $this->selectedType,
                    'table_id' => $this->selectedTable,
                    'mode_paiement_id' => $this->selectedModePaiement ?? null,
                    'observation' => $this->observation ?: 'No observation',
                    'user_id' => auth()->id(),
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                // Create order details for each item
                foreach ($this->panier as $item) {
                    DB::table('details_commandes')->insert([
                        'commande_id' => $commandeId,
                        'article_id' => $item['id'],
                        'message_id' => $item['message'],
                        'prix_ht' => $item['prix_ht'],
                        'tva' => $item['tva'],
                        'qte' => $item['quantity'],
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            // Generate appropriate tickets based on order type
            if ($this->selectedType == 1) {
                $this->generateKitchenTicket();
            } else {
                $this->generateTickets();
            }

            // Notify other components that table status has changed
            $this->dispatch('tableStatusUpdated');
            $this->dispatch('orderCreated');

            // Close modal and reset cart
            $this->showModal = false;
            $this->resetCart();

            $this->successMessage = 'Order created successfully!';

        } catch (\Exception $e) {
            Log::error('Payment confirmation error: ' . $e->getMessage());
            $this->errorMessage = 'Payment failed: ' . $e->getMessage();
        }
    }


    private function resetCart()
    {
        $this->panier = [];
        $this->total = 0;
        $this->sousTotal = 0;
        $this->observation = null;
        $this->selectedModePaiement = null;
    }


    public function generateKitchenTicket()
    {
        try {
            $commande = Commande::with(['details.article', 'table'])
                ->where('etat_id', Etat::TEMPORARY)
                ->where('table_id', $this->selectedTable)
                ->latest()
                ->first();

            if ($commande) {
                return $this->generatePDF('pdf.kitchen', compact('commande'), "kitchen-ticket-{$commande->id}.pdf");
            }
        } catch (\Exception $e) {
            Log::error('Error generating kitchen ticket: ' . $e->getMessage());
            $this->errorMessage = 'Failed to generate kitchen ticket: ' . $e->getMessage();
        }
    }


    public function generateTickets()
    {
        try {
            $commande = Commande::with(['details.article', 'table', 'modePaiement', 'user'])
                ->where('etat_id', Etat::CONFIRMED)
                ->where('table_id', $this->selectedTable)
                ->latest()
                ->first();

            if ($commande) {
                $this->generatePDF('pdf.order', compact('commande'), "order-{$commande->id}.pdf");
                return $this->generatePDF('pdf.kitchen', compact('commande'), "kitchen-ticket-{$commande->id}.pdf");
            }
        } catch (\Exception $e) {
            Log::error('Error generating tickets: ' . $e->getMessage());
            $this->errorMessage = 'Failed to generate tickets: ' . $e->getMessage();
        }
    }


    private function generatePDF($view, $data, $filename)
    {
        try {
            $pdf = PDF::loadView($view, $data);
            return response()->streamDownload(
                fn() => print ($pdf->output()),
                $filename
            );
        } catch (\Exception $e) {
            Log::error('PDF generation error: ' . $e->getMessage());
            $this->errorMessage = 'Error generating PDF: ' . $e->getMessage();
            return null;
        }
    }

    public function updatedSearch()
    {
        if ($this->selectedSousFamille) {
            $this->loadArticles($this->selectedSousFamille);
        }
    }

    public function render()
    {
        return view('livewire.main');
    }
}
