<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['type'];
    
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}