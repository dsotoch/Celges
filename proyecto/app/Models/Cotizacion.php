<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{

    protected $fillable = [
        'cliente',
        'codigo',
        'destino',
        'subtotal',
        'total',
        'utilidad',
        'envio',
        'encomienda',
        'facturacion',
        'favor',
        'pendiente',
        'persona_id',
        'created_at',
        'estado'
    ];

    public function cliente()
    {
        return $this->belongsTo(Persona::class);
    }
    public function productos()
    {
        return $this->hasMany(Cotizacion_Producto::class, "cotizacion_id");
    }
}
