<?php

use App\Recipe;
use Illuminate\Database\Seeder;

class RecipesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 3; $i++) {
            Recipe::create([
                'name' => 'Общий рецепт №' . $i,
                'text' => $faker->text,
                'picture' => asset('images/recipe-red-pepper-deviled-eggs.jpg'),
            ]);
        }

        for ($i = 1; $i <= 3; $i++) {
            Recipe::create([
                'name' => 'зецепт пользователя admin' . $i,
                'text' => $faker->text,
                'user_id' => 1
            ]);

        }


    }
}
