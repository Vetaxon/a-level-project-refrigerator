<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Vitali',
            'email' => 'vitalii.ivanov1983@gmail.com',
            'password' => Hash::make('Vitali111'),
        ]);

        User::create([
            'name' => 'Vladimir',
            'email' => 'mailguntestlaravel1@gmail.com',
            'password' => Hash::make('Vladimir111'),
        ]);

        User::create([
            'name' => 'Alexey',
            'email' => 'ampmail@ukr.net',
            'password' => Hash::make('Alexey111'),
        ]);
    }
}
