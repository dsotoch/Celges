<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        "codigo",
        "tipo",
        "marca",
        "modelo",
        "capacidad",
        "persona_id"
    ];
    
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
