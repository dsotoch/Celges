<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuentas extends Model
{
    protected $fillable=[
      "venta_id",
      "total",
      "deuda",
      "saldo_a_favor"
    ];

    public function compra(){
        return $this->belongsTo(Compra::class);
    }
    public function venta(){
        return $this->belongsTo(Venta::class);
    }
    
}
