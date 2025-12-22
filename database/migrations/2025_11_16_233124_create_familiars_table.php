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
        Schema::create('familiares', function (Blueprint $table) {
    $table->id();
    $table->foreignId('encuesta_id')->constrained()->onDelete('cascade');
    $table->string('nombre_completo');
    $table->date('fecha_nacimiento')->nullable();
    $table->string('tipo_documento')->nullable();
    $table->string('documento')->nullable();
    $table->date('fecha_expedicion')->nullable();
    $table->string('lugar_expedicion')->nullable();
    $table->string('parentesco')->nullable();
    $table->string('genero')->nullable();
    $table->string('poblacion')->nullable();
    $table->string('condicion')->nullable();
    $table->boolean('sabe_leer')->nullable();
    $table->boolean('estudia')->nullable();
    $table->string('nivel_educativo')->nullable();
    $table->string('celular')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('familiars');
    }
};
