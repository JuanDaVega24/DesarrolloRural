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
        Schema::create('formulario_sesiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
                  ->constrained('proyectos_productivos')
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->string('session_token')->unique();
            $table->json('datos_beneficiarios')->default('[]'); // Datos temporales del usuario
            $table->boolean('completada')->default(false);
            $table->timestamp('ultima_actividad')->useCurrent();
            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index(['proyecto_id', 'user_id']);
            $table->index('session_token');
            $table->index('ultima_actividad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulario_sesiones');
    }
};