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

use Illuminate\Support\Facades\DB;


class Main extends Component
{
    public $familles;
    public $tables = [];
    public $sousFamilles = [];
    public $articles = [];
    public $messages = [];
    public $selectedTable = null;
    public $selectedType = null;
    public $selectedSousFamille = null;
    public $etat = [];
    public $selectedEtat;
    public $showModal = false;
    public $selectedModePaiement;
    public $goodToSet;
    public $activeFamille;
    public $type = [];
    public $modeDepaiment;
    public $panier = [];
    public $observation;
    public $total;
    public $sousTotal;


    public function mount()
    {
        $this->messages = Message::all();
        $this->familles = Famille::all();
        $this->tables = Table::all();
        $this->etat = Etat::all();
        $this->type = Type::all();
        $this->modeDepaiment = ModePaiement::all();
        $this->tables = Table::with([
            'latestCommande' => fn($query) => $query->with('etat')
        ])->get();
        $this->activeFamille = $this->familles[0]->id;
        $this->loadSousFamilles($this->activeFamille);
        $this->loadArticles($this->selectedSousFamille);

        DB::table('commandes')
            ->where('etat_id', 3)
            ->update(['etat_id' => 2]);

    }

    public function loadSousFamilles($familleId)
    {
        $this->activeFamille = $familleId;
        $this->sousFamilles = SousFamille::where('famille_id', $familleId)->get();
    }

    public function loadArticles($sousFamilleId)
    {
        $this->selectedSousFamille = $sousFamilleId;
        $this->articles = Article::where('sous_famille_id', $sousFamilleId)->get();
    }

    public function loadType($type)
    {
        $this->selectedType = $type;
    }

    public function addToPanier($articleId)
    {
        $article = Article::findOrfail($articleId);
        if (isset($this->panier[$articleId])) {
            $this->panier[$articleId]['quantity']++;
            DB::table('articles')->where('id', $articleId)->decrement('stock', 1);
            $this->sousTotal += $this->panier[$articleId]['prix_ht'];
            $this->total += $this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100);
        } else {
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
            DB::table('articles')->where('id', $articleId)->decrement('stock', 1);
            $this->sousTotal += $this->panier[$articleId]['prix_ht'];
            $this->total += $this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100);
        }
        $this->loadArticles($this->selectedSousFamille);
    }

    public function removeFromPanier($articleId)
{
    if (isset($this->panier[$articleId])) {
        DB::table('articles')
            ->where('id', $articleId)
            ->increment('stock', $this->panier[$articleId]['quantity']);
        
        $this->sousTotal -= $this->panier[$articleId]['prix_ht'] * $this->panier[$articleId]['quantity'];
        $this->total -= ($this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100)) * $this->panier[$articleId]['quantity'];
        
        unset($this->panier[$articleId]);

        $this->loadArticles($this->selectedSousFamille);
    }
}
    public function incOrDecQTE($articleId, $opr)
    {
        if ($opr == 'inc') {
            $this->panier[$articleId]['quantity']++;
            DB::table('articles')->where('id', $articleId)->increment('stock', 1);
            $this->sousTotal += $this->panier[$articleId]['prix_ht'];
            $this->total += $this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100);
        }
        if ($opr == 'dec') {
            if ($this->panier[$articleId]['quantity'] == 1) {
                $this->removeFromPanier($articleId);
                return;
            }
            $this->panier[$articleId]['quantity']--;
            DB::table('articles')->where('id', $articleId)->decrement('stock', 1);
            $this->sousTotal -= $this->panier[$articleId]['prix_ht'];
            $this->total -= $this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100);
        }
        $this->loadArticles($this->selectedSousFamille);
    }


    public function updateQuantity($articleId, $qte)
    {
        if (!is_numeric($qte) || !isset($this->panier[$articleId])) {
            return;
        }

        $qte = (int) $qte;
        $currentQte = $this->panier[$articleId]['quantity'];

        if ($qte < 1) {
            $this->removeFromPanier($articleId);
            $this->loadArticles($this->selectedSousFamille);
            return;
        }

        try {
            DB::beginTransaction();
            if ($currentQte < $qte) {
                $difference = $qte - $currentQte;
                $article = DB::table('articles')->find($articleId);
                if ($article->stock < $difference) {
                    throw new \Exception('Insufficient stock');
                }

                DB::table('articles')
                    ->where('id', $articleId)
                    ->decrement('stock', $difference);

                $this->sousTotal += $this->panier[$articleId]['prix_ht'] * $difference;
                $this->total += $this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100) * $difference;
            } elseif ($currentQte > $qte) {
                $difference = $currentQte - $qte;

                DB::table('articles')
                    ->where('id', $articleId)
                    ->increment('stock', $difference);

                $this->sousTotal -= $this->panier[$articleId]['prix_ht'] * $difference;
                $this->total -= $this->panier[$articleId]['prix_ht'] * (1 + $this->panier[$articleId]['tva'] / 100) * $difference;
            }

            if ($currentQte != $qte) {
                $this->panier[$articleId]['quantity'] = $qte;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }

        $this->loadArticles($this->selectedSousFamille);
    }

    public function processPayment()
    {
        $this->showModal = true;
    }

    public function confirmPayment()
    {

        DB::transaction(function () {
            $commandeId = DB::table('commandes')->insertGetId([
                'date' => now()->format('Y-m-d'),
                'heure' => now()->format('H:i:s'),
                'etat_id' => $this->selectedType == 1 ? Etat::TEMPORARY : Etat::CONFIRMED,
                'type_id' => $this->selectedType,
                'table_id' => $this->selectedTable,
                'mode_paiement_id' => $this->selectedModePaiement,
                'observation' => $this->observation ? $this->observation : 'No observation',
                'user_id' => auth()->id(),
            ]);

            foreach ($this->panier as $item) {
                DB::table('details_commandes')->insert([
                    'commande_id' => $commandeId,
                    'article_id' => $item['id'],
                    'message_id' => $item['message'],
                    'prix_ht' => $item['prix_ht'],
                    'tva' => $item['tva'],
                    'qte' => $item['quantity'],
                ]);

                Article::where('id', $item['id'])
                    ->decrement('stock', $item['quantity']);
            }

            if ($this->selectedType != 1) {
            }
        });

        $this->showModal = false;
        $this->resetCart();

    }

    private function resetCart()
    {
        $this->panier = [];
        $this->total = 0;
        $this->sousTotal = 0;
        $this->showModal = false;
        $this->selectedModePaiement = null;

        foreach ($this->panier as $item) {
            Article::where('id', $item['id'])->increment('stock', $item['quantity']);
        }
    }


    public function render()
    {
        return view('livewire.main');
    }
}