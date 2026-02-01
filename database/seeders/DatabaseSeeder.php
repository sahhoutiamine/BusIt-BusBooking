<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            VilleSeeder::class,
            CompanySeeder::class,
            UserSeeder::class,
            ProgrammeSeeder::class,
            TripSeeder::class,
            SeatSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}
