<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Segment;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reservation::truncate();

        $client = User::where('role', 'client')->first();
        
        $segment = Segment::first();

        if ($client && $segment) {
            Reservation::create([
                'user_id' => $client->id,
                'segment_id' => $segment->id,
                'date_reservation' => now(),
                'statut' => 'Payé',
                'siege_numero' => 12,
            ]);
            
             Reservation::create([
                'user_id' => $client->id,
                'segment_id' => $segment->id,
                'date_reservation' => now(),
                'statut' => 'Confirmé',
                'siege_numero' => 14,
            ]);
        }
    }
}
