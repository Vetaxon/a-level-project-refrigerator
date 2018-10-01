<?php

use App\RecipeIngredient;
use Illuminate\Database\Seeder;

class RecipeIngredientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        RecipeIngredient::create([
            'recipe_id' => 1,
            'ingredient_id' => 3,
            'amount' => '40'
        ]);

        RecipeIngredient::create([
            'recipe_id' => 1,
            'ingredient_id' => 5,
            'amount' => '30'
        ]);


        RecipeIngredient::create([
            'recipe_id' => 2,
            'ingredient_id' => 8,
            'amount' => '40'
        ]);

        RecipeIngredient::create([
            'recipe_id' => 2,
            'ingredient_id' => 5,
            'amount' => '30'
        ]);

        RecipeIngredient::create([
            'recipe_id' => 3,
            'ingredient_id' => 14,
            'amount' => '40'
        ]);

        RecipeIngredient::create([
            'recipe_id' => 3,
            'ingredient_id' => 13,
            'amount' => '30'
        ]);

        RecipeIngredient::create([
            'recipe_id' => 4,
            'ingredient_id' => 1,
            'amount' => '40'
        ]);

        RecipeIngredient::create([
            'recipe_id' => 4,
            'ingredient_id' => 2,
            'amount' => '30'
        ]);

        RecipeIngredient::create([
            'recipe_id' => 5,
            'ingredient_id' => 3,
            'amount' => '40'
        ]);

        RecipeIngredient::create([
            'recipe_id' => 5,
            'ingredient_id' => 4,
            'amount' => '30'
        ]);

        RecipeIngredient::create([
            'recipe_id' => 6,
            'ingredient_id' => 5,
            'amount' => '40'
        ]);

        RecipeIngredient::create([
            'recipe_id' => 6,
            'ingredient_id' => 6,
            'amount' => '30'
        ]);





    }
}
