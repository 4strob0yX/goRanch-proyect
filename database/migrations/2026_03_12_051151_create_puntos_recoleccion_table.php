<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puntos_recoleccion', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 100);           // "Plaza Principal", "Farmacia del Pueblo"
            $table->string('direccion');
            $table->geometry('ubicacion', subtype: 'point');

            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->useCurrent();
        });

        // Columna en conductores para el punto actual
        Schema::table('conductores', function (Blueprint $table) {
            $table->foreignId('punto_recoleccion_id')
                  ->nullable()
                  ->constrained('puntos_recoleccion')
                  ->onDelete('set null')
                  ->after('ubicacion_actual');
        });
    }

    public function down(): void
    {
        Schema::table('conductores', function (Blueprint $table) {
            $table->dropForeign(['punto_recoleccion_id']);
            $table->dropColumn('punto_recoleccion_id');
        });
        Schema::dropIfExists('puntos_recoleccion');
    }
};