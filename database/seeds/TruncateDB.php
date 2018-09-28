<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruncateDB extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("DELETE FROM users");
        DB::statement("ALTER TABLE users AUTO_INCREMENT=0");

        DB::statement("DELETE FROM refrigerators");
        DB::statement("ALTER TABLE refrigerators AUTO_INCREMENT=0");


        DB::statement("DELETE FROM recipes");
        DB::statement("ALTER TABLE recipes AUTO_INCREMENT=0");


        DB::statement("DELETE FROM recipe_ingredient");
        DB::statement("ALTER TABLE recipe_ingredient AUTO_INCREMENT=0");

        DB::statement("DELETE FROM ingredients");
        DB::statement("ALTER TABLE ingredients AUTO_INCREMENT=0");


    }
}
