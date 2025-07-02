<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion_Producto extends Model
{
    protected $table = "cotizacion__productos";

    protected $fillable = [
        'cotizacion_id',
        'producto_id',
        'cantidad',
        'precio',
        'registrado',
    ];
    
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
