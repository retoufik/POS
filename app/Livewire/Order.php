<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\{Commande, Article, Table, Etat, DetailCommande, Famille, SousFamille, Message, ModePaiement};
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Database\Eloquent\Collection;

class Order extends Component
{
    public $selectedTable;
    public $orders;
    public $editingOrder;
    public $sousFamilles = [];
    public $articlesToAdd = [];
    public $selectedFamille = null;
    public $selectedSousFamille = null;
    public $panier = [];
    public $familles;
    public $messages;
    public $modifiedItems = [];
    public $modesPaiement;

    protected $listeners = ['tableStatusUpdated' => 'loadOrders'];

    public function mount()
    {
        $this->familles = Famille::all();
        $this->messages = Message::all();
        $this->modesPaiement = ModePaiement::all();
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = Commande::with(['details.article', 'table', 'etat'])
            ->whereDate('date', now()->format('Y-m-d'))
            ->where('etat_id', Etat::TEMPORARY)
            ->when($this->selectedTable, fn($q) => $q->where('table_id', $this->selectedTable))
            ->orderByDesc('created_at')
            ->get();
    }

    public function startEditing($orderId)
    {
        $this->editingOrder = Commande::with(['details.article', 'details.message'])->findOrFail($orderId);
        $this->panier = $this->editingOrder->details->mapWithKeys(function ($detail) {
            return [
                $detail->article_id => [
                    'id' => $detail->article_id,
                    'qte' => $detail->qte,
                    'message_id' => $detail->message_id,
                    'prix_ht' => $detail->prix_ht,
                    'tva' => $detail->tva,
                    'designation' => $detail->article->designation
                ]
            ];
        })->toArray();
    }

    public function cancelEdit()
    {
        // Restore stock for items in the basket if editing was canceled
        if ($this->editingOrder) {
            $originalDetails = $this->editingOrder->details->keyBy('article_id');
            
            foreach ($this->panier as $articleId => $item) {
                $originalQty = isset($originalDetails[$articleId]) ? $originalDetails[$articleId]->qte : 0;
                $currentQty = $item['qte'];
                
                if ($currentQty > $originalQty) {
                    // Return extra items to stock
                    Article::find($articleId)->increment('stock', $currentQty - $originalQty);
                }
            }
        }
        
        $this->reset(['editingOrder', 'panier', 'modifiedItems']);
    }

    public function loadSousFamilles($familleId)
    {
        $this->selectedFamille = $familleId;
        $this->sousFamilles = SousFamille::where('famille_id', $familleId)->get();
        $this->selectedSousFamille = null;
        $this->articlesToAdd = [];
    }

    public function loadArticles($sousFamilleId)
    {
        $this->selectedSousFamille = $sousFamilleId;
        $this->articlesToAdd = Article::where('sous_famille_id', $sousFamilleId)
            ->where('stock', '>', 0)
            ->get() ?? Collection::make([]);
    }

    public function addToPanier($articleId)
    {
        $article = Article::findOrFail($articleId);
        
        // Check if article has enough stock
        if ($article->stock <= 0) {
            session()->flash('error', "Article '{$article->designation}' is out of stock.");
            return;
        }

        if (isset($this->panier[$articleId])) {
            $this->panier[$articleId]['qte']++;
        } else {
            $this->panier[$articleId] = [
                'id' => $articleId,
                'qte' => 1,
                'message_id' => null,
                'prix_ht' => $article->prix_ht,
                'tva' => $article->tva,
                'designation' => $article->designation
            ];
        }

        $article->decrement('stock', 1);
        $this->modifiedItems[$articleId] = true;
        
        // Refresh available articles
        if ($this->selectedSousFamille) {
            $this->loadArticles($this->selectedSousFamille);
        }
    }

    public function updateQuantity($articleId, $action)
    {
        $article = Article::find($articleId);
        
        if (!$article) {
            session()->flash('error', 'Article not found.');
            return;
        }
        
        if ($action === 'increment') {
            if ($article->stock > 0) {
                $this->panier[$articleId]['qte']++;
                $article->decrement('stock');
            } else {
                session()->flash('error', "Article '{$article->designation}' is out of stock.");
                return;
            }
        } else {
            if ($this->panier[$articleId]['qte'] > 1) {
                $this->panier[$articleId]['qte']--;
                $article->increment('stock');
            }
        }
        
        $this->modifiedItems[$articleId] = true;
    }

    public function updateMessageForItem($articleId, $messageId)
    {
        if (isset($this->panier[$articleId])) {
            $this->panier[$articleId]['message_id'] = $messageId == '' ? null : $messageId;
            $this->modifiedItems[$articleId] = true;
        }
    }

    public function removeFromPanier($articleId)
    {
        if (!isset($this->panier[$articleId])) {
            return;
        }
        
        $quantity = $this->panier[$articleId]['qte'];
        Article::find($articleId)->increment('stock', $quantity);
        unset($this->panier[$articleId]);
        
        // Refresh available articles
        if ($this->selectedSousFamille) {
            $this->loadArticles($this->selectedSousFamille);
        }
    }

    public function saveEditedOrder()
    {
        if (empty($this->panier)) {
            session()->flash('error', 'Cannot save an empty order.');
            return;
        }
        
        DB::transaction(function () {
            // Remove existing details
            $this->editingOrder->details()->delete();

            // Create new details
            foreach ($this->panier as $item) {
                DetailCommande::create([
                    'commande_id' => $this->editingOrder->id,
                    'article_id' => $item['id'],
                    'qte' => $item['qte'],
                    'prix_ht' => $item['prix_ht'],
                    'tva' => $item['tva'],
                    'message_id' => $item['message_id']
                ]);
            }

            $this->editingOrder->touch(); // Update timestamp
            
            session()->flash('success', 'Order updated successfully.');
        });
        
        $this->cancelEdit();
        $this->loadOrders();
        $this->dispatch('tableStatusUpdated');
    }

    public function confirmOrder($orderId)
    {
        DB::transaction(function () use ($orderId) {
            $commande = Commande::with(['details.article', 'table', 'etat'])->findOrFail($orderId);

            // Check if order has details
            if ($commande->details->isEmpty()) {
                session()->flash('error', 'Cannot confirm an empty order.');
                return;
            }

            // Generate PDF
            $pdf = PDF::loadView('pdf.order', compact('commande'));

            // Update order status
            $commande->update(['etat_id' => Etat::CONFIRMED]);

            // We don't delete details on CONFIRMED orders, as they should remain for historical purposes
            
            session()->flash('success', 'Order confirmed successfully.');
            
            $this->loadOrders();
            
            return response()->streamDownload(
                fn() => print($pdf->output()),
                "order-{$commande->id}.pdf"
            );
        });
        
        $this->dispatch('tableStatusUpdated');
    }

    public function cancelOrder($orderId)
    {
        DB::transaction(function () use ($orderId) {
            $commande = Commande::with('details.article')->findOrFail($orderId);

            // Restore stock
            foreach ($commande->details as $detail) {
                Article::where('id', $detail->article_id)
                    ->increment('stock', $detail->qte);
            }

            // Update order status to canceled
            $commande->update(['etat_id' => Etat::CANCELLED]);

            // We keep the details for historical purposes, but mark the order as canceled
            
            session()->flash('success', 'Order canceled successfully.');

            // Reload orders
            $this->loadOrders();
        });
        
        $this->dispatch('tableStatusUpdated');
    }

    public function getTotalHtAttribute()
    {
        return collect($this->panier)->sum(function($item) {
            return $item['prix_ht'] * $item['qte'];
        });
    }

    public function getTotalTvaAttribute()
    {
        return collect($this->panier)->sum(function($item) {
            return ($item['prix_ht'] * $item['tva'] / 100) * $item['qte'];
        });
    }

    public function getTotalTtcAttribute()
    {
        return $this->getTotalHtAttribute() + $this->getTotalTvaAttribute();
    }

    public function render()
    {
        return view('livewire.order', [
            'tables' => Table::all(),
            'totalHt' => $this->getTotalHtAttribute(),
            'totalTva' => $this->getTotalTvaAttribute(),
            'totalTtc' => $this->getTotalTtcAttribute(),
        ]);
    }
}