<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $table = 'tables';
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