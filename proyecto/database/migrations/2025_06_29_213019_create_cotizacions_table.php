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
        Schema::create('cotizacions', function (Blueprint $table) {
            $table->id();
            $table->string('cliente', 255);
            $table->string('codigo', 50)->unique();
            $table->string('destino', 255);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('utilidad', 10, 2)->nullable();
            $table->decimal('envio', 10, 2)->nullable();
            $table->decimal('encomienda', 10, 2)->nullable();
            $table->decimal('facturacion', 10, 2)->nullable();
            $table->decimal('favor', 10, 2)->nullable();
            $table->decimal('pendiente', 10, 2)->nullable();
            $table->foreignId("persona_id")->nullable()->constrained("personas")->onDelete("set null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacions');
    }
};
