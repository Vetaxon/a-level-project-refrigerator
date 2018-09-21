<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeIngredientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_ingredient', function (Blueprint $table)
        {
            $table->integer('recipe_id')->unsigned();
            $table->integer('ingredient_id')->unsigned();
            $table->integer('value')->unsigned();
            $table->timestamps();
            $table->primary(['recipe_id', 'ingredient_id']);
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_ingredient');
    }
}
