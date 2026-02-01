<?php

namespace Database\Seeders;

use App\Models\Seat;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = Trip::all();

        foreach ($trips as $trip) {
            // Generate 20 seats for each trip
            for ($i = 1; $i <= 20; $i++) {
                Seat::create([
                    'trip_id' => $trip->id,
                    'name' => 'S' . $i,
                    'is_available' => true,
                ]);
            }
        }
    }
}
