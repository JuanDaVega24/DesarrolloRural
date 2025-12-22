<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('encuestas', function (Blueprint $table) {
        $table->json('datos_extra')->nullable();
    });
}

public function down()
{
    Schema::table('encuestas', function (Blueprint $table) {
        $table->dropColumn('datos_extra');
    });
}

};
