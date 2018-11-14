<?php

use Illuminate\Database\Seeder;

class UsersConfirmationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->whereBetween('id', [1, 7])
            ->update(['confirmed' => 1]);
    }
}
