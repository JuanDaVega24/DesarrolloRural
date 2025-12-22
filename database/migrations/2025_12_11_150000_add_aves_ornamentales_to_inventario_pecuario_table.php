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
        Schema::table('inventario_pecuario', function (Blueprint $table) {
            $table->integer('aves_ornamentales')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventario_pecuario', function (Blueprint $table) {
            $table->dropColumn('aves_ornamentales');
        });
    }
};
