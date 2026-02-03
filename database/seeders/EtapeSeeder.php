<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Etape;
use App\Models\Gare;
use Illuminate\Database\Seeder;

class EtapeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Etape::truncate();
        
        // Helpers to find IDs
        $gares = Gare::all();
        $findGare = fn($name) => $gares->first(function($g) use ($name) {
            return str_contains($g->nom, $name);
        });

        // --- Route 1: Casablanca -> Rabat -> Tanger ---
        $route1 = Route::where('nom', 'Ligne Nord Express')->first();
        if ($route1) {
            $stops = [
                ['city' => 'Casablanca', 'time' => '08:00:00', 'order' => 1],
                ['city' => 'Rabat',      'time' => '09:15:00', 'order' => 2],
                ['city' => 'Tanger',     'time' => '13:00:00', 'order' => 3],
            ];
            $this->createEtapesForRoute($route1, $stops, $findGare);
        }

        // --- Route 2: Marrakech -> Casablanca -> Rabat -> Fes ---
        $route2 = Route::where('nom', 'Ligne Impériale')->first();
        if ($route2) {
            $stops = [
                ['city' => 'Marrakech',  'time' => '07:00:00', 'order' => 1],
                ['city' => 'Casablanca', 'time' => '10:30:00', 'order' => 2],
                ['city' => 'Rabat',      'time' => '11:45:00', 'order' => 3],
                ['city' => 'Fes',        'time' => '14:30:00', 'order' => 4],
            ];
            $this->createEtapesForRoute($route2, $stops, $findGare);
        }

        // --- Route 3: Agadir -> Marrakech ---
        $route3 = Route::where('nom', 'Ligne Atlas')->first();
        if ($route3) {
            $stops = [
                ['city' => 'Agadir',     'time' => '08:00:00', 'order' => 1],
                ['city' => 'Marrakech',  'time' => '11:30:00', 'order' => 2],
            ];
            $this->createEtapesForRoute($route3, $stops, $findGare);
        }
    }

    private function createEtapesForRoute($route, $stops, $findGare) {
        foreach ($stops as $stop) {
            $gare = $findGare($stop['city']);
            if ($gare) {
                Etape::create([
                    'route_id' => $route->id,
                    'gare_id' => $gare->id,
                    'ordre' => $stop['order'],
                    'heure_passage' => $stop['time'],
                ]);
            }
        }
    }
}
