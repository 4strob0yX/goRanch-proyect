<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conductores', function (Blueprint $table) {
            $table->id();
            
            //relacion
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            
            //datos del vehículo
            $table->enum('tipo_vehiculo', ['moto', 'bici', 'auto']);
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->string('placa', 20)->unique();
            
            //estado y ubicacion
            $table->boolean('esta_conectado')->default(false);
            $table->geometry('ubicacion_actual', subtype: 'point')->nullable(); // Nullable porque al inicio no tiene ubicación
            
            $table->enum('estatus', ['pendiente', 'activo', 'suspendido'])->default('pendiente');
            $table->decimal('calificacion_promedio', 3, 2)->default(5.00);
            
            
            $table->timestamp('creado_en')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conductores');
    }
};