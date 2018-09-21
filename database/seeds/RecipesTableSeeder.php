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


        //Recipe::truncate();
        for($i=1; $i<=8; $i++)
        {
            Recipe::create([
                'name' => 'рецепт' . $i,
                'text' => $faker->text,
                'user_id' => ceil($i/2),
            ]);
        }
    }
}
