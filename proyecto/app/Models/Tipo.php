<?php

namespace App\Models;

use App\Enums\EnumTipos;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $fillable = ["tipo"];

    protected $casts = ["tipo" => EnumTipos::class];
    public function persona()
    {
        return $this->hasMany(Persona::class);
    }
}
