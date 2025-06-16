<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cuenta_bancarias', function (Blueprint $table) {
            $table->id();
            $table->string("banco", 120);
            $table->enum('tipo_cuenta', [
                'Ahorros',
                'Corriente',
                'CCI',
                'Plazo Fijo',
                'Remuneraciones',
                'Yape',
                'Plin',
                'Transferencia Digital',
                'Otros'
            ]);
            $table->string("numero_cuenta", 120)->unique();
            $table->string("cci", 120)->nullable();
            $table->enum("moneda", ["PEN", "USD"]);
            $table->boolean("empresa")->default(false);
            $table->boolean("activo")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_bancarias');
    }
};
