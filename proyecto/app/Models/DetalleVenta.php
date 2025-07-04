<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = "detalle_ventas";
    protected $fillable = [
        'venta_id',
        'producto_id',
        'imei',
        'descripcion',
        'precio_unitario',
        'cantidad',
        'subtotal',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
