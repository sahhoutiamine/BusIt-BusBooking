<?php

namespace Database\Seeders;

use App\Models\Trip;
use App\Models\Company;
use App\Models\Programme;
use App\Models\Ville;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get companies
        $ctm = Company::where('name', 'CTM')->first();
        $supratours = Company::where('name', 'Supratours')->first();
        $alsa = Company::where('name', 'ALSA')->first();

        // Get cities
        $casablanca = Ville::where('nom', 'Casablanca')->first();
        $rabat = Ville::where('nom', 'Rabat')->first();
        $marrakech = Ville::where('nom', 'Marrakech')->first();
        $fes = Ville::where('nom', 'Fès')->first();
        $tanger = Ville::where('nom', 'Tanger')->first();
        $agadir = Ville::where('nom', 'Agadir')->first();

        // Get programmes
        $programmes = Programme::where('is_active', true)->get();

        $trips = [
            // Casablanca - Rabat trips
            [
                'company_id' => $ctm->id,
                'programme_id' => $programmes[0]->id ?? 1,
                'ville_depart_id' => $casablanca->id,
                'ville_arrivee_id' => $rabat->id,
                'date_depart' => Carbon::today()->addDays(1)->format('Y-m-d'),
                'heure_depart' => '08:00:00',
                'heure_arrivee' => '09:30:00',
                'distance' => 87.5,
                'tarif' => 45.00,
            ],
            [
                'company_id' => $supratours->id,
                'programme_id' => $programmes[1]->id ?? 2,
                'ville_depart_id' => $casablanca->id,
                'ville_arrivee_id' => $rabat->id,
                'date_depart' => Carbon::today()->addDays(1)->format('Y-m-d'),
                'heure_depart' => '14:00:00',
                'heure_arrivee' => '15:30:00',
                'distance' => 87.5,
                'tarif' => 50.00,
            ],
            // Casablanca - Marrakech trips
            [
                'company_id' => $ctm->id,
                'programme_id' => $programmes[2]->id ?? 3,
                'ville_depart_id' => $casablanca->id,
                'ville_arrivee_id' => $marrakech->id,
                'date_depart' => Carbon::today()->addDays(2)->format('Y-m-d'),
                'heure_depart' => '07:00:00',
                'heure_arrivee' => '10:30:00',
                'distance' => 240.0,
                'tarif' => 120.00,
            ],
            [
                'company_id' => $alsa->id,
                'programme_id' => $programmes[3]->id ?? 4,
                'ville_depart_id' => $casablanca->id,
                'ville_arrivee_id' => $marrakech->id,
                'date_depart' => Carbon::today()->addDays(2)->format('Y-m-d'),
                'heure_depart' => '16:00:00',
                'heure_arrivee' => '19:30:00',
                'distance' => 240.0,
                'tarif' => 130.00,
            ],
            // Rabat - Fès trip
            [
                'company_id' => $supratours->id,
                'programme_id' => $programmes[4]->id ?? 5,
                'ville_depart_id' => $rabat->id,
                'ville_arrivee_id' => $fes->id,
                'date_depart' => Carbon::today()->addDays(3)->format('Y-m-d'),
                'heure_depart' => '09:00:00',
                'heure_arrivee' => '12:30:00',
                'distance' => 200.0,
                'tarif' => 100.00,
            ],
            // Tanger - Casablanca trip
            [
                'company_id' => $ctm->id,
                'programme_id' => $programmes[5]->id ?? 6,
                'ville_depart_id' => $tanger->id,
                'ville_arrivee_id' => $casablanca->id,
                'date_depart' => Carbon::today()->addDays(4)->format('Y-m-d'),
                'heure_depart' => '06:00:00',
                'heure_arrivee' => '11:00:00',
                'distance' => 340.0,
                'tarif' => 170.00,
            ],
            // Marrakech - Agadir trip
            [
                'company_id' => $alsa->id,
                'programme_id' => $programmes[6]->id ?? 7,
                'ville_depart_id' => $marrakech->id,
                'ville_arrivee_id' => $agadir->id,
                'date_depart' => Carbon::today()->addDays(5)->format('Y-m-d'),
                'heure_depart' => '10:00:00',
                'heure_arrivee' => '13:30:00',
                'distance' => 250.0,
                'tarif' => 125.00,
            ],
        ];

        foreach ($trips as $trip) {
            Trip::create($trip);
        }
    }
}
