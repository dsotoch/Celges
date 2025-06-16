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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string("codigo");
            $table->string("nombres");
            $table->string("ruc")->nullable();
            $table->string("direccion")->nullable();
            $table->string("telefono")->nullable();
            $table->string("email")->nullable();
            $table->foreignId("tipo_id")->nullable()->constrained("tipos")->onDelete("set null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
