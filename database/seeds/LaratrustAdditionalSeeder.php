<?php

use App\User;
use Illuminate\Database\Seeder;

class LaratrustAdditionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::find(1)->attachRole('administrator');
        User::find(2)->attachRole('guest');
    }
}
