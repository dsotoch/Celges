<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = [
        "numero",
        "persona_id",
        "tipo_compra",
        "fecha_compra",
        "numero_documento",
        "tipo_documento",
        "total",
        "estado"
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
