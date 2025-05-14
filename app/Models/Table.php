<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Table extends Model
{
    protected $table = 'tables';
    protected $guarded = ['id'];
    
    protected $casts = [
        'X' => 'float',
        'Y' => 'float',
        'Numero' => 'integer'
    ];

    public function latestCommande(): HasOne
    {
        return $this->hasOne(Commande::class)->latestOfMany();
    }

    public function activeCommande(): HasOne
    {
        return $this->hasOne(Commande::class)
            ->where('etat_id', Etat::where('name', 'occupee')->first()->id)
            ->latestOfMany();
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }
    public function latestTodayCommande(): HasOne
    {
        return $this->hasOne(Commande::class)
            ->whereDate('created_at', today())
            ->latest();
    }
}