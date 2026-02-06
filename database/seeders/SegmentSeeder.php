<?php

namespace Database\Seeders;

use App\Models\Segment;
use App\Models\Programme;
use App\Models\Bus;
use App\Models\Etape;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SegmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Segment::truncate();
        \Illuminate\Support\Facades\DB::table('programme_segment')->truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $buses = Bus::where('statut', 'active')->get();
        if ($buses->isEmpty()) return;

        $programmes = Programme::with('route.etapes')->get();
        
        // This array will track segments already created for a specific (StartGare, EndGare, Time)
        // Format: $sharedSegments[start_id][end_id][time] = Segment object
        $sharedSegments = [];
        $busIndex = 0;

        foreach ($programmes as $programme) {
            $etapes = $programme->route->etapes->sortBy('ordre')->values();
            $heureDepart = $programme->heure_depart;

            for ($i = 0; $i < $etapes->count(); $i++) {
                for ($j = $i + 1; $j < $etapes->count(); $j++) {
                    $startEtape = $etapes[$i];
                    $endEtape = $etapes[$j];
                    
                    $startGareId = $startEtape->gare_id;
                    $endGareId = $endEtape->gare_id;

                    // Check if we already created a segment for this leg at this time
                    if (isset($sharedSegments[$startGareId][$endGareId][$heureDepart])) {
                        $segment = $sharedSegments[$startGareId][$endGareId][$heureDepart];
                        $segment->programmes()->attach($programme->id);
                        continue;
                    }

                    // Otherwise, create a new one
                    $steps = $j - $i;
                    $tarif = 40.00 + ($steps * 45.00);
                    if ($steps > 2) $tarif *= 0.9;
                    
                    $startTimeRef = Carbon::parse($startEtape->heure_passage);
                    $endTimeRef = Carbon::parse($endEtape->heure_passage);
                    $secondsDiff = abs($endTimeRef->diffInSeconds($startTimeRef));
                    
                    $durationStr = gmdate('H:i:s', $secondsDiff);
                    $distKm = $steps * 110.5;

                    // Assign a bus
                    $bus = $buses[$busIndex % $buses->count()];
                    
                    $segment = Segment::create([
                        'bus_id' => $bus->id,
                        'start_gare_id' => $startGareId,
                        'end_gare_id' => $endGareId,
                        'tarif' => round($tarif, 2),
                        'duree_estimee' => $durationStr,
                        'distance_km' => $distKm,
                    ]);

                    $segment->programmes()->attach($programme->id);

                    // Track this segment to avoid duplicates
                    $sharedSegments[$startGareId][$endGareId][$heureDepart] = $segment;
                    
                    // Increment bus index for NEXT unique segment type
                    $busIndex++;
                }
            }
        }
    }
}
