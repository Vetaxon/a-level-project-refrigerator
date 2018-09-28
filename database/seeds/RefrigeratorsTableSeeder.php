<?php

use App\Refrigerator;
use Illuminate\Database\Seeder;

class RefrigeratorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 9; $i++) {
            Refrigerator::create([
                'user_id' => 1,
                'ingredient_id' => $i,
                'amount' => '50 g',
            ]);
        }

        for ($i = 10; $i <= 16; $i++) {
            Refrigerator::create([
                'user_id' => 2,
                'ingredient_id' => $i,
                'amount' => '50 g',
            ]);
        }
    }
}
