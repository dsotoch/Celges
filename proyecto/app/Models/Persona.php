<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{

    protected $fillable = [
        "codigo",
        "nombres",
        "ruc",
        "direccion",
        "telefono",
        "email",
        "tipo_id"
    ];

    public function  producto()
    {
        return $this->hasMany(Producto::class);
    }
    public function  tipo()
    {
        return $this->belongsTo(Tipo::class);
    }
}
