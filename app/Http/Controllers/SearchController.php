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
            'passengers_count' => 'nullable|integer|min:1|max:10',
        ]);

        $passengersCount = $request->input('passengers_count', 1);
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
        // 1. One of its Programmes runs on this day
        // 2. Start Gare is in Start Ville
        // 3. End Gare is in End Ville
        $directSegments = Segment::whereHas('programmes', function($q) use ($dayName) {
            $q->where('jour_depart', $dayName)
              ->where('is_active', true);
        })
        ->whereHas('startGare', function($q) use ($request) {
            $q->where('ville_id', $request->ville_depart_id);
        })
        ->whereHas('endGare', function($q) use ($request) {
            $q->where('ville_id', $request->ville_arrivee_id);
        })
        ->with(['programmes' => function($q) use ($dayName) {
             $q->where('jour_depart', $dayName);
        }, 'bus', 'startGare.ville', 'endGare.ville'])
        ->get()
        ->sortBy(function($segment) {
            return $segment->programmes->first()->heure_depart;
        });

        // Indirect Routes (Simplification: limiting to this example for now)
        $indirectRoutes = [];
        
        return view('search.results', [
            'directTrips' => $directSegments, 
            'indirectRoutes' => $indirectRoutes,
            'searchDate' => $dateDepart,
            'passengersCount' => $passengersCount
        ]);
    }
}
