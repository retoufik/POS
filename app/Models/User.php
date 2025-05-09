<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $guarded = ['id'];
    protected $hidden = ['password'];
    
    protected $casts = [
        'isAdmin' => 'boolean'
    ];

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}