<?php

namespace Database\Seeders;

use App\Models\Programme;
use App\Models\Ville;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some cities
        $casablanca = Ville::where('nom', 'Casablanca')->first();
        $rabat = Ville::where('nom', 'Rabat')->first();
        $marrakech = Ville::where('nom', 'Marrakech')->first();
        $fes = Ville::where('nom', 'Fès')->first();
        $tanger = Ville::where('nom', 'Tanger')->first();
        $agadir = Ville::where('nom', 'Agadir')->first();

        $programmes = [
            // Casablanca - Rabat
            [
                'ville_depart_id' => $casablanca->id,
                'ville_arrivee_id' => $rabat->id,
                'heure_depart' => '08:00:00',
                'heure_arrivee' => '09:30:00',
                'is_active' => true,
            ],
            [
                'ville_depart_id' => $casablanca->id,
                'ville_arrivee_id' => $rabat->id,
                'heure_depart' => '14:00:00',
                'heure_arrivee' => '15:30:00',
                'is_active' => true,
            ],
            // Casablanca - Marrakech
            [
                'ville_depart_id' => $casablanca->id,
                'ville_arrivee_id' => $marrakech->id,
                'heure_depart' => '07:00:00',
                'heure_arrivee' => '10:30:00',
                'is_active' => true,
            ],
            [
                'ville_depart_id' => $casablanca->id,
                'ville_arrivee_id' => $marrakech->id,
                'heure_depart' => '16:00:00',
                'heure_arrivee' => '19:30:00',
                'is_active' => true,
            ],
            // Rabat - Fès
            [
                'ville_depart_id' => $rabat->id,
                'ville_arrivee_id' => $fes->id,
                'heure_depart' => '09:00:00',
                'heure_arrivee' => '12:30:00',
                'is_active' => true,
            ],
            // Tanger - Casablanca
            [
                'ville_depart_id' => $tanger->id,
                'ville_arrivee_id' => $casablanca->id,
                'heure_depart' => '06:00:00',
                'heure_arrivee' => '11:00:00',
                'is_active' => true,
            ],
            // Marrakech - Agadir
            [
                'ville_depart_id' => $marrakech->id,
                'ville_arrivee_id' => $agadir->id,
                'heure_depart' => '10:00:00',
                'heure_arrivee' => '13:30:00',
                'is_active' => true,
            ],
            // Inactive programme
            [
                'ville_depart_id' => $fes->id,
                'ville_arrivee_id' => $tanger->id,
                'heure_depart' => '15:00:00',
                'heure_arrivee' => '19:00:00',
                'is_active' => false,
            ],
        ];

        foreach ($programmes as $programme) {
            Programme::create($programme);
        }
    }
}
