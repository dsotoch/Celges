<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlmacenInterno extends Model
{
    protected $fillable = ["compra_id", "producto_id", "imei", "color", "precio_compra", "precio_venta", "cantidad", "registrado"];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
