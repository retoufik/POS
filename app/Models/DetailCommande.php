<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailCommande extends Model
{
    protected $table = "details_commandes";
    protected $guarded = ["id"];
    
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}