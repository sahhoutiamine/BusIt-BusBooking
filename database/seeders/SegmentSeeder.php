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
        Segment::truncate();

        $programmes = Programme::with('route.etapes.gare')->get();
        $buses = Bus::where('statut', 'active')->get();
        
        if ($buses->isEmpty()) return;

        foreach ($programmes as $index => $programme) {
            // Assign a bus cyclically
            $bus = $buses[$index % $buses->count()];
            
            $etapes = $programme->route->etapes->sortBy('ordre')->values();
            
            // Generate segments for ALL pairs (i, j) where i < j
            for ($i = 0; $i < $etapes->count(); $i++) {
                for ($j = $i + 1; $j < $etapes->count(); $j++) {
                    $startEtape = $etapes[$i];
                    $endEtape = $etapes[$j];
                    
                    // Simple logic to calculate price and duration
                    // Assume ~30DH per step (station hop)
                    // Assume ~1.5 hours per step
                    
                    $steps = $j - $i; // Number of hops
                    $basePrice = 40.00;
                    $pricePerStep = 45.00;
                    $tarif = $basePrice + ($steps * $pricePerStep);
                    
                    // Reduce price for long trips slightly
                    if ($steps > 2) $tarif *= 0.9;
                    
                    $startContent = Carbon::parse($startEtape->heure_passage);
                    $endContent = Carbon::parse($endEtape->heure_passage);
                    
                    // Adjust duration based on program specific start time offset?
                    // For now, let's just use the diff from current etapes
                    // If etape times are 08:00 and 09:15, diff is 1h15.
                    
                    // However, Programme has its own heure_depart (e.g. 14:00 vs 08:00).
                    // We should shift the etape times.
                    // Let's assume etape->heure_passage is relative to 00:00 or base route time.
                    // For simplicity, let's just calculate duration from Etapes strictly.
                    
                    // Etape 1: 08:00, Etape 2: 09:15. Diff = 75 mins.
                    // If Programme starts at 14:00.
                    // Segment Start = 14:00 (if Etape 1) or 14:00 + (Etape_N - Etape_1).
                    // Segment Duration = Etape_End - Etape_Start.
                    
                    // Problem: Etapes defined in RouteSeeder have fixed times (e.g. 08:00).
                    // If programme is 14:00, we should preserve DURATION.
                    
                    $startTimeRef = Carbon::parse($startEtape->heure_passage);
                    $endTimeRef = Carbon::parse($endEtape->heure_passage);
                    
                    // Handle day wrap if needed (not here for simple bus trips)
                    $secondsDiff = $endTimeRef->diffInSeconds($startTimeRef);
                    if ($endTimeRef->lessThan($startTimeRef)) {
                         // assume next day? or just error in data.
                         // Let's use absolute difference
                         $secondsDiff = $startTimeRef->diffInSeconds($endTimeRef); 
                    }
                    
                    $durationStr = gmdate('H:i:s', $secondsDiff);
                    
                    // Kilometers approximation: 100km per step
                    $distKm = $steps * 110.5;

                    Segment::create([
                        'programme_id' => $programme->id,
                        'bus_id' => $bus->id,
                        'start_gare_id' => $startEtape->gare_id,
                        'end_gare_id' => $endEtape->gare_id,
                        'tarif' => round($tarif, 2),
                        'duree_estimee' => $durationStr, // Duration of this specific leg
                        'distance_km' => $distKm,
                    ]);
                }
            }
        }
    }
}
