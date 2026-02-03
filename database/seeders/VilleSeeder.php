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
        Ville::truncate();

        $villes = [
            'Casablanca',
            'Rabat',
            'Marrakech',
            'Tanger',
            'Fes',
            'Agadir',
            'Meknes',
            'Oujda',
        ];

        foreach ($villes as $ville) {
            Ville::create(['name' => $ville]);
        }
    }
}
