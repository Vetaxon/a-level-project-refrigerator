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
        for($i=16; $i>=1; $i--)
        {
            RecipeIngredient::create([
                'recipe_id' => ceil((17-$i)/2),
                'ingredient_id' => $i,
                'value' => $i * 10,
            ]);
        }
    }
}
