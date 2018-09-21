<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //User::truncate();

        User::create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin'),
        ]);

        for($i=1; $i<=3; $i++) {
            User::create([
                'name' => 'user' . $i,
                'email' => 'user' . $i . '@test.com',
                'password' => Hash::make('user' . $i),
            ]);
        }
    }
}
