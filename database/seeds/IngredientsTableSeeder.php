<?php

use App\Ingredient;
use Illuminate\Database\Seeder;

class IngredientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Ingredient::create([
            'name' => 'морковь',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'мука',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'вода',
        ]);
        Ingredient::create([
            'name' => 'сахар',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'сыр',
        ]);
        Ingredient::create([
            'name' => 'яблоко',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'лук',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'подсолничное масло',
        ]);
        Ingredient::create([
            'name' => 'уксус',
        ]);
        Ingredient::create([
            'name' => 'сода',
            'user_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'соль',
            'user_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'перец',
            'user_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'говядина',
        ]);
        Ingredient::create([
            'name' => 'свинина',
        ]);
        Ingredient::create([
            'name' => 'баранина',
        ]);
        Ingredient::create([
            'name' => 'курятина',
            'user_id' => 2,
        ]);
    }
}
