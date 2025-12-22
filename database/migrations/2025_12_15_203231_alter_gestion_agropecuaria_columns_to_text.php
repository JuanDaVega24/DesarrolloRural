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
        Schema::table('gestion_agropecuaria', function (Blueprint $table) {
            $table->string('participa')->nullable()->change();
            $table->string('credito')->nullable()->change();
            $table->string('aprobado')->nullable()->change();
            $table->string('tiene_creditos')->nullable()->change();
            $table->string('trabajo_colectivo')->nullable()->change();
            $table->text('fuentes')->nullable()->change();
            $table->text('cuantos')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gestion_agropecuaria', function (Blueprint $table) {
            $table->boolean('participa')->nullable()->change();
            $table->boolean('credito')->nullable()->change();
            $table->boolean('aprobado')->nullable()->change();
            $table->boolean('tiene_creditos')->nullable()->change();
            $table->boolean('trabajo_colectivo')->nullable()->change();
            $table->string('fuentes')->nullable()->change();
            $table->integer('cuantos')->nullable()->change();
        });
    }
};
