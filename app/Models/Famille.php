<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Famille extends Model
{
    protected $guarded = ['id'];
    
    public function sousFamilles()
    {
        return $this->hasMany(SousFamille::class);
    }

    public function articles()
    {
        return $this->hasManyThrough(Article::class, SousFamille::class);
    }
}