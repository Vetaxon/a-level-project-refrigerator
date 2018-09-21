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
            'measure_id' => 3,
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'мука',
            'measure_id' => 1,
            'user_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'вода',
            'measure_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'сахар',
            'measure_id' => 1,
            'user_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'сыр',
            'measure_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'яблоко',
            'measure_id' => 3,
            'user_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'лук',
            'measure_id' => 3,
            'user_id' => 3,
        ]);
        Ingredient::create([
            'name' => 'подсолничное масло',
            'measure_id' => 2,
        ]);
        Ingredient::create([
            'name' => 'уксус',
            'measure_id' => 3,
        ]);
        Ingredient::create([
            'name' => 'сода',
            'measure_id' => 4,
            'user_id' => 3,
        ]);
        Ingredient::create([
            'name' => 'соль',
            'measure_id' => 6,
            'user_id' => 4,
        ]);
        Ingredient::create([
            'name' => 'перец',
            'measure_id' => 6,
            'user_id' => 4,
        ]);
        Ingredient::create([
            'name' => 'говядина',
            'measure_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'свинина',
            'measure_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'баранина',
            'measure_id' => 1,
        ]);
        Ingredient::create([
            'name' => 'курятина',
            'measure_id' => 1,
            'user_id' => 4,
        ]);
    }
}
