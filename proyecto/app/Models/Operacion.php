<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operacion extends Model
{
    protected $table="operaciones";
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
     public function pago()
    {
        return $this->hasMany(Pagos::class);
    }
}
