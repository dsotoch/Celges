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
        Schema::table('cotizacions', function (Blueprint $table) {
            $table->enum("estado",["Pendiente","Generado","Anulado"])->default("Pendiente");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotizacions', function (Blueprint $table) {
            $table->dropColumn("estado");
        });
    }
};
