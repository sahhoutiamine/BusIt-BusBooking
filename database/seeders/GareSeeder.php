<?php

namespace Database\Seeders;

use App\Models\Gare;
use App\Models\Ville;
use Illuminate\Database\Seeder;

class GareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gare::truncate();

        $villes = Ville::all();

        foreach ($villes as $ville) {
            Gare::create([
                'nom' => 'Gare Routière ' . $ville->name,
                'adresse' => 'Centre Ville, ' . $ville->name,
                'ville_id' => $ville->id,
            ]);
            
            // Add a secondary station for larger cities
            if (in_array($ville->name, ['Casablanca', 'Rabat', 'Tanger'])) {
                Gare::create([
                    'nom' => 'Gare ' . $ville->name . ' Nord',
                    'adresse' => 'Quartier Nord, ' . $ville->name,
                    'ville_id' => $ville->id,
                ]);
            }
        }
    }
}
