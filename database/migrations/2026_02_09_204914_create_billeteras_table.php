<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billeteras', function (Blueprint $table) {
            $table->id();
            
            // relacion con usuarios 
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            
            $table->decimal('saldo', 10, 2)->default(0.00);
            
            // fecha actualizada
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billeteras');
    }
};