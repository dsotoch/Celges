<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbonoVenta extends Model
{
    protected $fillable = [
        'venta_id',
        'fecha',
        'monto',
        'metodo_pago',
        'operacion_id',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
    public function operacion()
    {
        return $this->belongsTo(Operacion::class);
    }
}
