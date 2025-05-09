<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'prix_variable' => 'boolean',
        'gerer_stock' => 'boolean',
        'facturation_poids' => 'boolean'
    ];
    
    public function sousFamille()
    {
        return $this->belongsTo(SousFamille::class);
    }

    public function detailsCommandes()
    {
        return $this->hasMany(DetailCommande::class);
    }
}
