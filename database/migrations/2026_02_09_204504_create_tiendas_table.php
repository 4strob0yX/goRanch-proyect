<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiendas', function (Blueprint $table) {
            $table->id();
            
            // relacion con usuarios (admin de la tienda)
            $table->foreignId('admin_id')->constrained('usuarios')->onDelete('cascade');
            
            $table->string('nombre_tienda', 100);
            $table->text('descripcion')->nullable();
            $table->string('imagen_portada')->nullable();
            $table->string('direccion');
            
            // coordenadas
            $table->geometry('ubicacion', subtype: 'point'); 
            
            // enum
            $table->enum('estatus', ['abierto', 'cerrado', 'suspendido'])->default('cerrado');
            
        
            $table->timestamp('creado_en')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiendas');
    }
};