<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operacion extends Model
{
    protected $fillable=[
        "numero",
        "tipo",
        "monto",
        "fecha",
        "cuenta_id"
    ];
    
    public function cuenta(){
        return $this->belongsTo(CuentaBancaria::class);
    }
}
