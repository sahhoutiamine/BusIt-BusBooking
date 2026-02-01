<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some passengers
        $passenger1 = User::where('email', 'mohammed@example.com')->first();
        $passenger2 = User::where('email', 'amina@example.com')->first();

        // Get a trip
        $trip = Trip::first();

        if ($trip && $passenger1) {
            // Book a seat for passenger 1
            $seat1 = Seat::where('trip_id', $trip->id)->where('name', 'S1')->first();
            
            if ($seat1) {
                Reservation::create([
                    'user_id' => $passenger1->id,
                    'trip_id' => $trip->id,
                    'seat_id' => $seat1->id,
                    'date_reservation' => Carbon::now(),
                    'status' => 'paid',
                    'siege_numero' => 1,
                ]);

                // Update seat availability
                $seat1->update(['is_available' => false]);
            }
        }

        if ($trip && $passenger2) {
            // Book a seat for passenger 2
            $seat2 = Seat::where('trip_id', $trip->id)->where('name', 'S2')->first();
            
            if ($seat2) {
                Reservation::create([
                    'user_id' => $passenger2->id,
                    'trip_id' => $trip->id,
                    'seat_id' => $seat2->id,
                    'date_reservation' => Carbon::now(),
                    'status' => 'pending',
                    'siege_numero' => 2,
                ]);

                // Update seat availability
                $seat2->update(['is_available' => false]);
            }
        }
    }
}
