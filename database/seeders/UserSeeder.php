<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();

        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@busit.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0600000000',
            'remember_token' => Str::random(10),
        ]);

        // Create Clients
        User::create([
            'name' => 'Client One',
            'email' => 'client1@busit.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '0611111111',
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'Client Two',
            'email' => 'client2@busit.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '0622222222',
            'remember_token' => Str::random(10),
        ]);
    }
}
