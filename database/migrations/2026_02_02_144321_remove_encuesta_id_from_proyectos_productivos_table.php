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
        Schema::table('proyectos_productivos', function (Blueprint $table) {
            $table->dropForeign(['encuesta_id']);
            $table->dropColumn('encuesta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyectos_productivos', function (Blueprint $table) {
             $table->foreignId('encuesta_id')->nullable()->constrained()->nullOnDelete();
        });
    }
};
