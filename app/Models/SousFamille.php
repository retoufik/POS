<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SousFamille extends Model
{
    protected $guarded = ['id'];
    
    public function famille()
    {
        return $this->belongsTo(Famille::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
