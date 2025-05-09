<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etat extends Model
{
    const TEMPORARY = 1;
    const CONFIRMED = 2;
    const CANCELLED = 3;
    const TABLE_AVAILABLE = 3;

    protected $guarded = ['id'];
    
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}