<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etat extends Model
{
    public const TEMPORARY = 1;
    public const CONFIRMED = 2;
    public const CANCELLED = 3;

    protected $guarded = ['id'];

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
    public function latestCommande()
    {
        return $this->hasOne(Commande::class)->latestOfMany();
    }
}