<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrectIngredientsRecipesDropMeasureTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropUnique('ingredients_name_unique');
            $table->dropForeign('ingredients_measure_id_foreign');
            $table->dropIndex('ingredients_measure_id_foreign');
            $table->dropColumn('measure_id');
            $table->string('measure', 100)->after('name');
        });

        Schema::dropIfExists('measures');

        Schema::table('recipes', function (Blueprint $table) {
            $table->dropUnique('recipes_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            //
        });
    }
}
