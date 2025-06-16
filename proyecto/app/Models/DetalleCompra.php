<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $fillable = ["producto_id", "compra_id", "imei", "color", "precio", "cantidad", "registrado"];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }
}
