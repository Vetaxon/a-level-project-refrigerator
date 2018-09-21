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
        for($i=1; $i<=16; $i++)
        {
            Refrigerator::create([
                'user_id' => ceil($i/4),
                'ingredient_id' => $i,
                'value' => mt_rand(1, 100),
            ]);
        }
    }
}
