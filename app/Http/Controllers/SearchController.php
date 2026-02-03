<?php

namespace App\Http\Controllers;

use App\Models\Segment;
use App\Models\Ville;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function index()
    {
        $villes = Ville::orderBy('name')->get();
        return view('search.index', compact('villes'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'ville_depart_id' => 'required|exists:villes,id',
            'ville_arrivee_id' => 'required|exists:villes,id',
            'date_depart' => 'required|date',
        ]);

        $dateDepart = Carbon::parse($request->date_depart);
        // Map English day names to French as stored in DB (e.g. 'Monday' -> 'Lundi')
        // Or simply strict match if DB uses English. ProgrammeSeeder used French ('Lundi')
        $daysMap = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche',
        ];

        $dayName = $daysMap[$dateDepart->format('l')] ?? $dateDepart->format('l');

        // Direct Segments
        // Find segments where:
        // 1. Programme runs on this day
        // 2. Start Gare is in Start Ville
        // 3. End Gare is in End Ville
        $directSegments = Segment::whereHas('programme', function($q) use ($dayName) {
            $q->where('jour_depart', $dayName)
              ->where('is_active', true);
        })
        ->whereHas('startGare', function($q) use ($request) {
            $q->where('ville_id', $request->ville_depart_id);
        })
        ->whereHas('endGare', function($q) use ($request) {
            $q->where('ville_id', $request->ville_arrivee_id);
        })
        ->with(['programme', 'bus', 'startGare.ville', 'endGare.ville'])
        ->get()
        ->sortBy(function($segment) {
            return $segment->programme->heure_depart;
        });

        // Indirect Routes (Simplification: limiting to this example for now)
        $indirectRoutes = [];
        
        // TODO: Implement sophisticated indirect routing if needed. 
        // For now returning empty to focus on Direct functionality.

        return view('search.results', [
            'directTrips' => $directSegments, 
            'indirectRoutes' => $indirectRoutes,
            'searchDate' => $dateDepart
        ]);
    }
}
