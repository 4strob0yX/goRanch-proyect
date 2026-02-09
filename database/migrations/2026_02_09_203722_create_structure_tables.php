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
      
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); 
            
            $table->string('nombre');
            $table->string('email')->unique();
            $table->string('telefono', 20)->unique();
            $table->string('password');
            $table->string('foto_perfil')->nullable();
            $table->string('token_fcm')->nullable();

           
            $table->enum('rol', ['usuario', 'conductor', 'admin_tienda', 'super_admin'])->default('usuario');
            $table->enum('estatus', ['activo', 'bloqueado'])->default('activo');

            
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

         
            $table->rememberToken(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};