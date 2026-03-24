<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->foreignId('punto_recoleccion_id')
                ->nullable()
                ->after('tienda_id')
                ->constrained('puntos_recoleccion')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropForeign(['punto_recoleccion_id']);
            $table->dropColumn('punto_recoleccion_id');
        });
    }
};
