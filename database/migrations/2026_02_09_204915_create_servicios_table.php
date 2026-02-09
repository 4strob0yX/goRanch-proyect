<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            
           
            $table->foreignId('cliente_id')->constrained('usuarios')->onDelete('cascade');
            
         
            $table->foreignId('conductor_id')->nullable()->constrained('conductores')->onDelete('set null');
            
       
            $table->foreignId('tienda_id')->nullable()->constrained('tiendas')->onDelete('set null');
            
            
            $table->enum('tipo', ['viaje', 'mandado_libre', 'delivery_tienda']);
            $table->enum('estatus', ['buscando', 'aceptado', 'en_sitio', 'en_ruta', 'completado', 'cancelado'])->default('buscando');
            
            
            $table->string('direccion_origen');
            $table->string('direccion_destino');
            
            
            $table->geometry('ubicacion_origen', subtype: 'point');
            $table->geometry('ubicacion_destino', subtype: 'point');
            
            $table->decimal('distancia_km', 8, 2)->nullable();
            
        
            $table->decimal('costo_envio', 10, 2);
            $table->decimal('costo_productos', 10, 2)->default(0); // Para mandados
            $table->decimal('tarifa_plataforma', 10, 2);
            $table->decimal('total_final', 10, 2);
            
            $table->enum('metodo_pago', ['efectivo', 'billetera']);
            $table->text('notas')->nullable();
            
            // tiempos
            $table->timestamp('iniciado_en')->nullable();
            $table->timestamp('finalizado_en')->nullable();
            $table->timestamp('creado_en')->useCurrent();
    
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};