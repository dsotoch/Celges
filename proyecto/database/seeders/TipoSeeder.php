<?php

namespace Database\Seeders;

use App\Enums\EnumTipos;
use App\Models\Tipo;
use Illuminate\Database\Seeder;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tipo::create([
            "tipo" => EnumTipos::ambos,
        ]);
        Tipo::create([
            "tipo" => EnumTipos::cliente,
        ]);
        Tipo::create([
            "tipo" => EnumTipos::proveedor,
        ]);
    }
}
