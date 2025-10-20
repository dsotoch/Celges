<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $fillable = [
        'usuario_id',
        'fecha_apertura',
        'fecha_cierre',
        'monto_inicial',
        'monto_final',
        'estado',
        'observacion'
    ];

    public  function persona()
    {
        return $this->belongsTo(User::class, "usuario_id");
    }
}
