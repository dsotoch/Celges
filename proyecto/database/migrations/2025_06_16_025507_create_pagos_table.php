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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_pago');
            $table->foreignId('servicio_id')->nullable()->constrained('servicios')->onDelete('set null'); // Si es pago de servicio fijo
            $table->decimal('monto_pagado', 10, 2);
            $table->string('metodo_pago', 100)->nullable(); // Yape, Efectivo, Transferencia, etc.
            $table->foreignId('operacion_id')->nullable()->constrained('operaciones')->onDelete('cascade'); // Relación con operación
            $table->foreignId('persona_id')->nullable()->constrained('personas')->onDelete('set null'); // Relación con operación
            $table->text('nota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
