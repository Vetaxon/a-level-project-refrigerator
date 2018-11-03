<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    const FILENAME = 'database/data/data.json';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TruncateDB::class);
        $this->call(UsersTableSeeder::class);
        if (file_exists(self::FILENAME)) {
            $this->call(AllRecipesDataFromJSONSeeder::class);
        } else {
            $this->call(IngredientsTableSeeder::class);
            $this->call(RecipesTableSeeder::class);
            $this->call(RecipeIngredientTableSeeder::class);
        }
        $this->call(RefrigeratorsTableSeeder::class);
        $this->call(LaratrustSeeder::class);
    }
}
