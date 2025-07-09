<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallesCuentas extends Model
{
    protected $table = "detalles_cuentas";

    protected $fillable = [
        "cuenta_id",
        "operacion_id",
    ];

    public function cuenta()
    {
        return $this->belongsTo(Cuentas::class);
    }
    
    public function operacion()
    {
        return $this->belongsTo(Operacion::class);
    }
}
