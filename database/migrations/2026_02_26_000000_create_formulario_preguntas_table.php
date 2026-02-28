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
        Schema::create('formulario_preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
                  ->constrained('proyectos_productivos')
                  ->onDelete('cascade');
            $table->text('pregunta');
            $table->text('subtitulo')->nullable();
            $table->enum('tipo_campo', ['texto', 'numero', 'fecha', 'select', 'checkbox']);
            $table->json('opciones')->nullable(); // Para selects y checkboxes
            $table->boolean('es_obligatorio')->default(false);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulario_preguntas');
    }
};