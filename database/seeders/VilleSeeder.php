<?php

namespace Database\Seeders;

use App\Models\Ville;
use Illuminate\Database\Seeder;

class VilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $villes = [
            ['nom' => 'Casablanca'],
            ['nom' => 'Rabat'],
            ['nom' => 'Marrakech'],
            ['nom' => 'Fès'],
            ['nom' => 'Tanger'],
            ['nom' => 'Agadir'],
            ['nom' => 'Meknès'],
            ['nom' => 'Oujda'],
            ['nom' => 'Kenitra'],
            ['nom' => 'Tétouan'],
            ['nom' => 'Safi'],
            ['nom' => 'El Jadida'],
        ];

        foreach ($villes as $ville) {
            Ville::create($ville);
        }
    }
}
