<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bus::truncate();

        // Create 20 buses
        for ($i = 1; $i <= 20; $i++) {
            Bus::create([
                'matricule' => '1234-A-' . (50 + $i),
                'capacite' => 50,
                'statut' => 'active',
            ]);
        }
        
        // Add a few buses in maintenance
        Bus::create([
            'matricule' => '9999-B-88',
            'capacite' => 45,
            'statut' => 'maintenance',
        ]);
        
        Bus::create([
            'matricule' => '8888-D-12',
            'capacite' => 55,
            'statut' => 'maintenance',
        ]);
    }
}
