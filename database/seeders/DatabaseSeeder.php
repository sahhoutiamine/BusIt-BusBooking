<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key constraints to allow truncating
        Schema::disableForeignKeyConstraints();

        $this->call([
            UserSeeder::class,
            VilleSeeder::class,
            GareSeeder::class,
            BusSeeder::class,
            RouteSeeder::class,
            EtapeSeeder::class,
            ProgrammeSeeder::class,
            SegmentSeeder::class,
            ReservationSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
