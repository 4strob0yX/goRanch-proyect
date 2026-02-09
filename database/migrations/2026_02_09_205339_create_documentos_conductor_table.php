<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_conductor', function (Blueprint $table) {
            $table->id();

            // Relación con conductor
            $table->foreignId('conductor_id')->constrained('conductores')->onDelete('cascade');

            // Detalles del documento
            $table->string('tipo_documento', 50);
            $table->string('url_archivo'); // ruta
            $table->enum('estatus', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('comentarios_rechazo')->nullable();

            // Fechas
            $table->timestamp('subido_en')->useCurrent();
            $table->timestamp('revisado_en')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_conductor');
    }
};