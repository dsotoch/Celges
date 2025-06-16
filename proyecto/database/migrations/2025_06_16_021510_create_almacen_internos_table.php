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
        Schema::create('almacen_internos', function (Blueprint $table) {
            $table->id();
            $table->integer("cantidad");
            $table->boolean("registrado")->default(false);
            $table->string("imei", 100);
            $table->string("color", 100)->nullable();
            $table->double("precio_compra", 10, 2);
            $table->double("precio_venta", 10, 2);
            $table->foreignId("compra_id")->constrained("compras")->onDelete("cascade");
            $table->foreignId("producto_id")->constrained("productos")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('almacen_internos');
    }
};
