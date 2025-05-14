<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'table_id',
        'type_id',
        'mode_paiement_id',
        'etat_id',
        'total_ht',
        'total_ttc',
        'note',
        'created_at',
        'updated_at',
    ];

    protected $guarded = ['id'];
    
    protected $casts = [
        'date' => 'date',
        'heure' => 'datetime',
    ];
    
    public function details()
    {
        return $this->hasMany(DetailCommande::class);
    }
    
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
    
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
    
    public function modePaiement()
    {
        return $this->belongsTo(ModePaiement::class);
    }
    
    public function etat()
    {
        return $this->belongsTo(Etat::class, 'etat_id');
    }
    
    public function messages()
    {
        return $this->belongsToMany(Message::class, 'commande_messages')
            ->withTimestamps();
    }
}

