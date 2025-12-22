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
        Schema::create('afectaciones', function (Blueprint $table) {
    $table->id();
    $table->foreignId('encuesta_id')->constrained()->onDelete('cascade');
    $table->json('actividad_productiva'); //si - ahora guarda array como JSON
    $table->year('anio')->nullable(); //si
    $table->string('semestre')->nullable();//si
    $table->string('fenomeno')->nullable();


    $table->decimal('hectareas', 10, 2)->nullable();
    $table->integer('unidades_afectadas')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afectacions');
    }
};
