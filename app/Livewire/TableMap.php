<?php

namespace App\Livewire;

use App\Models\{Table, Etat, Commande};
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;

class TableMap extends Component
{
    public $tables = [];
    public $activeFilter = 'all';
    public $searchQuery = '';

    protected $listeners = ['refreshTables' => 'loadTables'];

    public function mount()
    {
        $this->loadTables();
    }

   public function loadTables()
{
    $this->tables = Table::with(['latestTodayCommande.etat'])
        ->get()
        ->map(function ($table) {
            $status = $table->latestTodayCommande?->etat ?? Etat::find(2);
            
            return [
                'id' => $table->id,
                'number' => $table->Numero,
                'x' => $table->X,
                'y' => $table->Y,
                'status' => [
                    'id' => $status->id,
                    'name' => $status->etat,
                    'color' => $status->color
                ],
                'command' => $table->latestTodayCommande ? [
                    'id' => $table->latestTodayCommande->id,
                    'time' => $table->latestTodayCommande->created_at->diffForHumans(),
                ] : null
            ];
        })->toArray();
}

    public function getFilteredTables()
    {
        return collect($this->tables)
            ->when($this->activeFilter !== 'all', fn($tables) => 
                $tables->where('status.id', $this->activeFilter)
            )
            ->when($this->searchQuery, fn($tables) => 
                $tables->filter(fn($t) => str_contains((string)$t['number'], $this->searchQuery))
            )->values();
    }

    public function render()
    {
        return view('livewire.table-map', [
            'filteredTables' => $this->getFilteredTables(),
            'statusFilters' => Etat::all()
        ]);
    }
}