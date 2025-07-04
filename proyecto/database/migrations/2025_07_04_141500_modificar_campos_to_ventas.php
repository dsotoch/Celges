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
        Schema::table('ventas', function (Blueprint $table) {
            $table->enum('tipo_venta', ['Contado', 'Credito', 'Mixto'])->nullable()->change();

            // Cambiar enum de estado
            $table->dropColumn('estado'); // eliminar columna antigua

            $table->enum('estado', ['Empacado', 'Enviado', 'Despachado', 'Deuda','Pendiente', 'Pagado', 'Anulado'])->after('tipo_venta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn('estado'); // quitar nuevo estado

            // Restaurar estado anterior
            $table->enum('estado', ['Pendiente', 'Pagado', 'Anulado'])->after('tipo_venta');

            // Restaurar tipo_venta como obligatorio
            $table->enum('tipo_venta', ['Contado', 'Credito', 'Mixto'])->nullable(false)->change();
        });
    }
};
