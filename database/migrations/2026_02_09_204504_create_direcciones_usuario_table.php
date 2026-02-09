<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('direcciones_usuario', function (Blueprint $table) {
            $table->id();
            
            //relación con usuarios
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            
            $table->string('alias', 50)->comment('Casa, Chamba, etc.');
            $table->string('direccion');
            
          
            $table->geometry('ubicacion', subtype: 'point'); 

          
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
          
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direcciones_usuario');
    }
};