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
        Schema::create('detalles_cuentas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("cuenta_id")->nullable()->constrained("cuentas")->onDelete("set null");
            $table->foreignId("operacion_id")->nullable()->constrained("operaciones")->onDelete("set null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_cuentas');
    }
};
