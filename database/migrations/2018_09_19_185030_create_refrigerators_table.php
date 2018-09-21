<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefrigeratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refrigerators', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('ingredient_id')->unsigned();
            $table->integer('value')->unsigned();
            $table->primary(['user_id', 'ingredient_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refrigerators');
    }
}
