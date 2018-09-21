<?php

use App\Ingredient;
use App\Recipe;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(MeasuresTableSeeder::class);
        $this->call(IngredientsTableSeeder::class);
        $this->call(RecipesTableSeeder::class);
        $this->call(RecipeIngredientTableSeeder::class);
        $this->call(RefrigeratorsTableSeeder::class);
    }
}
