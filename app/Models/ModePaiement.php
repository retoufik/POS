<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModePaiement extends Model
{
    protected $fillable = ['mode_paiement'];
    
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}
