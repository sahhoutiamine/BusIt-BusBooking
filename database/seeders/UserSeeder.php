<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create owners
        User::create([
            'name' => 'Ahmed Alami',
            'email' => 'ahmed.owner@busit.ma',
            'password' => Hash::make('password'),
            'user_type' => 'owner',
        ]);

        User::create([
            'name' => 'Fatima Bennis',
            'email' => 'fatima.owner@busit.ma',
            'password' => Hash::make('password'),
            'user_type' => 'owner',
        ]);

        // Create passengers
        User::create([
            'name' => 'Mohammed Tazi',
            'email' => 'mohammed@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'passenger',
        ]);

        User::create([
            'name' => 'Amina Idrissi',
            'email' => 'amina@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'passenger',
        ]);

        User::create([
            'name' => 'Youssef Benjelloun',
            'email' => 'youssef@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'passenger',
        ]);

        User::create([
            'name' => 'Khadija El Fassi',
            'email' => 'khadija@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'passenger',
        ]);
    }
}
