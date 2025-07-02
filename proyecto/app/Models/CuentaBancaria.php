<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaBancaria extends Model
{
    protected $fillable = [
        "banco",
        "tipo_cuenta",
        "numero_cuenta",
        "cci",
        "moneda",
        "empresa",
        "titular",
        "activo"
    ];
    public function operacion(){
        return $this->hasMany(Operacion::class);
    }
}
