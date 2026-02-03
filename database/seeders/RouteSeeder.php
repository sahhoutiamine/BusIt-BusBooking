<?php

namespace Database\Seeders;

use App\Models\Route;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Route::truncate();
        
        // Route 1: Casablanca -> Rabat -> Tanger
        Route::create([
            'nom' => 'Ligne Nord Express',
            'description' => 'Liaison rapide entre Casablanca et Tanger via Rabat',
        ]);

        // Route 2: Marrakech -> Casablanca -> Rabat -> Fes
        Route::create([
            'nom' => 'Ligne Impériale',
            'description' => 'Connectant les villes impériales: Marrakech, Rabat et Fès',
        ]);

        // Route 3: Agadir -> Marrakech
        Route::create([
            'nom' => 'Ligne Atlas',
            'description' => 'Liaison directe Agadir - Marrakech',
        ]);
    }
}
