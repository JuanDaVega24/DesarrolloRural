<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('veredas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('corregimiento_id')->constrained();
    $table->string('nombre');
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('veredas');
    }
};
