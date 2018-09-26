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
        //Ingredient::truncate();

        Ingredient::create([
            'name' => 'морковь',
            'measure' => 'шт',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'мука',
            'measure' => 'кг',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'вода',
            'measure' => 'л',
        ]);
        Ingredient::create([
            'name' => 'сахар',
            'measure' => 'кг',
            'user_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'сыр',
            'measure' => 'кг',
        ]);
        Ingredient::create([
            'name' => 'яблоко',
            'measure' => 'кг',
            'user_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'лук',
            'measure' => 'кг',
            'user_id' => 3,
        ]);
        Ingredient::create([
            'name' => 'подсолничное масло',
            'measure' => 'л',
        ]);
        Ingredient::create([
            'name' => 'уксус',
            'measure' => 'л',
        ]);
        Ingredient::create([
            'name' => 'сода',
            'measure' => 'г',
            'user_id' => 3,
        ]);
        Ingredient::create([
            'name' => 'соль',
            'measure' => 'г',
            'user_id' => 4,
        ]);
        Ingredient::create([
            'name' => 'перец',
            'measure' => 'г',
            'user_id' => 4,
        ]);
        Ingredient::create([
            'name' => 'говядина',
            'measure' => 'кг',
        ]);
        Ingredient::create([
            'name' => 'свинина',
            'measure' => 'кг',
        ]);
        Ingredient::create([
            'name' => 'баранина',
            'measure' => 'кг',
        ]);
        Ingredient::create([
            'name' => 'курятина',
            'measure' => 'кг',
            'user_id' => 4,
        ]);
    }
}
