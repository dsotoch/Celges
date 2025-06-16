<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'fecha',
        'tipo_venta',
        'estado',
        'cliente_id',
        'total',
        'abono_inicial',
        'saldo_pendiente',
        'saldo_a_favor',
        'comision_facturacion',
        'nota',
    ];

    public function cliente()
    {
        return $this->belongsTo(Persona::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function abonos()
    {
        return $this->hasMany(AbonoVenta::class);
    }

}
