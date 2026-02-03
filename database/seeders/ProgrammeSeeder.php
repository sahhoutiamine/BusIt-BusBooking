<?php

namespace Database\Seeders;

use App\Models\Programme;
use App\Models\Route;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Programme::truncate();

        $routes = Route::all();
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        foreach ($routes as $route) {
            // Create schedules for almost every day with a few slight time variations
            foreach ($days as $day) {
                
                // Base departure time from Etape order 1, but we can have multiple programmes per day
                // e.g., one at 08:00, one at 14:00
                
                // Morning Bus
                // Get duration from etapes roughly? simple assumption: uses base route times
                // We'll just define start/end times manually relative to route norms
                
                $durationHours = 5; // Default fallback
                if ($route->nom == 'Ligne Impériale') $durationHours = 7.5;
                if ($route->nom == 'Ligne Atlas') $durationHours = 3.5;

                // Morning
                Programme::create([
                    'route_id' => $route->id,
                    'jour_depart' => $day,
                    'heure_depart' => '08:00:00',
                    'heure_arrivee' => date('H:i:s', strtotime('08:00:00') + $durationHours * 3600),
                    'is_active' => true,
                ]);

                // Afternoon (except Sunday maybe)
                if ($day !== 'Dimanche') {
                    Programme::create([
                        'route_id' => $route->id,
                        'jour_depart' => $day,
                        'heure_depart' => '14:00:00',
                        'heure_arrivee' => date('H:i:s', strtotime('14:00:00') + $durationHours * 3600),
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
