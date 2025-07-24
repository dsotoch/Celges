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
        Schema::table('abono_ventas', function (Blueprint $table) {
            $table->string("validado",100)->default("no");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abono_ventas', function (Blueprint $table) {
            $table->dropColumn("validado");
        });
    }
};
