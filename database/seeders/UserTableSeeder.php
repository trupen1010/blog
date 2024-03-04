<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'is_admin' => true,
            'is_guest' => false,
            'name' => 'Trupen Adroja',
            'email' => 'adrojatrupen@gmail.com',
            'password' => Hash::make('Trupen@10'),
        ]);

        User::create([
            'is_admin' => false,
            'is_guest' => true,
            'name' => 'Guest',
            'email' => 'guest@gmail.com',
            'password' => Hash::make('Trupen@Guest@10'),
        ]);
    }
}
