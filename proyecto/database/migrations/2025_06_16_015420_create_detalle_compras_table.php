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
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->id();
            $table->string("imei", 100);
            $table->string("color", 100)->nullable();
            $table->double("precio", 10, 2);
            $table->integer("cantidad");
            $table->boolean("registrado")->default(false);
            $table->foreignId("producto_id")->nullable()->constrained("productos")->onDelete("set null");
            $table->foreignId("compra_id")->nullable()->constrained("compras")->onDelete("set null");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_compras');
    }
};
