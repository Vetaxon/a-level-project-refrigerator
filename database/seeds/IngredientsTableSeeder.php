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
            'name' => 'Mорковь',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'Mука',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'Вода',
        ]);
        Ingredient::create([
            'name' => 'Сахар',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'Сыр',
        ]);
        Ingredient::create([
            'name' => 'Яблоко',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'Лук',
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'Подсолничное Масло',
        ]);
        Ingredient::create([
            'name' => 'Уксус',
        ]);
        Ingredient::create([
            'name' => 'Сода',
            'user_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'Соль',
            'user_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'Серец',
            'user_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'Говядина',
        ]);
        Ingredient::create([
            'name' => 'Свинина',
        ]);
        Ingredient::create([
            'name' => 'Баранина',
        ]);
        Ingredient::create([
            'name' => 'Курятина',
            'user_id' => 2,
        ]);
    }
}
