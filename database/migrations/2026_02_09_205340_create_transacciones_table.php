<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();

            // Relación con la billetera
            $table->foreignId('billetera_id')->constrained('billeteras')->onDelete('cascade');

            // Detalles del movimiento
            $table->enum('tipo', ['recarga', 'pago_servicio', 'cobro_servicio', 'retiro']);
            $table->decimal('monto', 10, 2);
            $table->string('referencia_externa')->nullable(); // ID de PayPal o Stripe si usas
            
            // Fecha exacta
            $table->timestamp('fecha')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};