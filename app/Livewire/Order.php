<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\{Commande, Article, Table, Etat, DetailCommande, Famille, SousFamille, Message};
use Illuminate\Support\Facades\{DB, Log};
use Barryvdh\DomPDF\Facade\Pdf as PDF;


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

    public function mount()
    {
        $this->familles = Famille::all();
        $this->messages = Message::all();
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = Commande::with(['details.article', 'table'])
            ->whereDate('date', now())
            ->where('etat_id', Etat::CONFIRMED)
            ->when($this->selectedTable, fn($q) => $q->where('table_id', $this->selectedTable))
            ->orderByDesc('created_at')
            ->get();
    }

    public function startEditing($orderId)
    {
        $this->editingOrder = Commande::with('details.article')->findOrFail($orderId);
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
        $this->editingOrder = null;
        $this->panier = [];
        $this->modifiedItems = [];
    }

    public function loadSousFamilles($familleId)
    {
        $this->selectedFamille = $familleId;
        $this->sousFamilles = SousFamille::where('famille_id', $familleId)->get();
        $this->selectedSousFamille = null;
        $this->articlesToAdd = [];
    }

    // Update loadArticles method
    public function loadArticles($sousFamilleId)
    {
        $this->selectedSousFamille = $sousFamilleId;
        $this->articlesToAdd = Article::where('sous_famille_id', $sousFamilleId)
            ->where('stock', '>', 0)
            ->get() ?? [];
    }

    public function addToPanier($articleId)
    {
        $article = Article::findOrFail($articleId);

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
    }

    public function updateQuantity($articleId, $action)
    {
        if ($action === 'increment') {
            $this->panier[$articleId]['qte']++;
            Article::find($articleId)->decrement('stock');
        } else {
            if ($this->panier[$articleId]['qte'] > 1) {
                $this->panier[$articleId]['qte']--;
                Article::find($articleId)->increment('stock');
            }
        }
    }

    public function removeFromPanier($articleId)
    {
        $quantity = $this->panier[$articleId]['qte'];
        Article::find($articleId)->increment('stock', $quantity);
        unset($this->panier[$articleId]);
    }

    public function saveEditedOrder()
    {
        DB::transaction(function () {
            // Remove existing details
            DetailCommande::where('commande_id', $this->editingOrder->id)->delete();

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
            $this->cancelEdit();
            $this->loadOrders();
        });
    }

    public function confirmOrder($orderId)
    {
        DB::transaction(function () use ($orderId) {
            $commande = Commande::with(['details.article', 'table'])->findOrFail($orderId);

            // Generate PDF
            $pdf = PDF::loadView('pdf.order', compact('commande'));

            // Update order status
            $commande->update(['etat_id' => 2]);

            // Delete details
            DetailCommande::where('commande_id', $orderId)->delete();

            return response()->streamDownload(
                fn() => print ($pdf->output()),
                "order-{$commande->id}.pdf"
            );
        });
    }

    public function cancelOrder($orderId)
    {
        DB::transaction(function () use ($orderId) {
            $commande = Commande::with('details')->findOrFail($orderId);

            // Restore stock
            foreach ($commande->details as $detail) {
                Article::where('id', $detail->article_id)
                    ->increment('stock', $detail->qte);
            }

            // Update order status to canceled (assuming etat_id 3 is canceled)
            $commande->update(['etat_id' => Etat::CANCELLED]);

            // Optionally delete details (if needed)
            // $commande->details()->delete();

            // Reload orders
            $this->loadOrders();
        });
    }

    public function render()
    {
        return view('livewire.order', [
            'tables' => Table::all(),
        ]);
    }
}