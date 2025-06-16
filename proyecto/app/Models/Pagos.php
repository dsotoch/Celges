<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
     protected $fillable = [
        'fecha_pago',
        'servicio_id',
        'monto_pagado',
        'metodo_pago',
        'operacion_id',
        'persona_id',
        'nota',
    ];

    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }
      public function operacion(){
        return $this->belongsTo(Operacion::class);
    }
     public function persona(){
        return $this->belongsTo(Persona::class);
    }
}
