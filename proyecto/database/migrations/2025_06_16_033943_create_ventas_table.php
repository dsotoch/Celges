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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->enum('tipo_venta', ['Contado', 'Credito', 'Mixto']);
            $table->enum('estado', ['Pendiente', 'Pagado', 'Anulado']);
            $table->foreignId('cliente_id')->constrained('personas')->onDelete('cascade');
            $table->double('total', 10, 2);
            $table->double('abono_inicial', 10, 2)->default(0);
            $table->double('saldo_pendiente', 10, 2)->default(0);
            $table->double('saldo_a_favor', 10, 2)->default(0);
            $table->double('comision_facturacion', 10, 2)->default(0);
            $table->text('nota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
