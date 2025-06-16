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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string("numero");
            $table->enum("tipo_compra", ['contado', 'credito', 'mixto']);
            $table->date("fecha_compra");
            $table->string("numero_documento")->nullable();
            $table->enum("tipo_documento", ['boleta', 'factura', 'otro']);
            $table->double("total", 10,2);
            $table->enum("estado", ['pendiente', 'pagado', 'anulado']);
            $table->foreignId("persona_id")->constrained("personas")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
