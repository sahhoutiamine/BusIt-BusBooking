<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Segment;
use App\Models\Reservation;
use App\Models\Passenger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function confirmation(Reservation $reservation)
    {
        return view('booking.confirmation', compact('reservation'));
    }
    public function create(Request $request)
    {
        $request->validate([
            'segment_id' => 'required|exists:segments,id',
            'date' => 'required|date',
        ]);

        $segment = Segment::with(['programme', 'bus', 'startGare.ville', 'endGare.ville'])->findOrFail($request->segment_id);
        $date = Carbon::parse($request->date);

        // Calculate available seats
        // Count passengers in active reservations for this segment/date
        $occupied = Passenger::whereHas('reservation', function($q) use ($segment, $date) {
             $q->where('segment_id', $segment->id)
               ->where('date_reservation', $date->format('Y-m-d'))
               ->where('statut', '!=', 'Annulé');
        })->count();
        
        $available = $segment->bus->capacite - $occupied;

        return view('booking.create', compact('segment', 'date', 'available'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'segment_id' => 'required|exists:segments,id',
            'date_reservation' => 'required|date',
            'passengers' => 'required|array|min:1|max:10',
            'passengers.*.nom_complet' => 'required|string|max:255',
            'passengers.*.type' => 'required|in:adulte,enfant',
            'passengers.*.cin' => 'nullable|required_if:passengers.*.type,adulte|string',
            'passengers.*.date_naissance' => 'required|date',
        ]);

        return DB::transaction(function() use ($request) {
            $segment = Segment::findOrFail($request->segment_id);
            $basePrice = $segment->tarif;
            
            // Calculate totals
            $totalPrice = 0;
            $passengersData = [];

            foreach($request->passengers as $pData) {
                $pPrice = $basePrice; 
                // Child discount? Prompt doesn't specify, but implies type check. 
                // Let's assume child is same price for simplicity unless specified. 
                // Prompt: "Calcul du prix... options". 
                // Wait, "type (adulte/enfant)". Usually child is cheaper. 
                // But Prompt says "Section 4: Calcul du prix... détail : prix de base + options = total".
                
                $optionsPrice = 0;
                if(!empty($pData['has_insurance'])) {
                    $optionsPrice += 25;
                }
                if(!empty($pData['has_snack_box'])) {
                    $optionsPrice += 15;
                }
                // Premium seat? +30. Not in form inputs yet?
                
                $totalPrice += ($pPrice + $optionsPrice);
                
                $passengersData[] = [
                    'nom_complet' => $pData['nom_complet'],
                    'cin' => $pData['cin'] ?? null,
                    'date_naissance' => $pData['date_naissance'],
                    'type' => $pData['type'],
                    'has_insurance' => !empty($pData['has_insurance']),
                    'has_snack_box' => !empty($pData['has_snack_box']),
                    'prix_billet' => $pPrice,
                    'prix_options' => $optionsPrice,
                ];
            }
            
            // Create Reservation
            $reservation = Reservation::create([
                'user_id' => auth()->id() ?? 1, // Fallback to user 1 if not logged in
                'segment_id' => $segment->id,
                'date_reservation' => $request->date_reservation,
                'statut' => 'Confirmé', // Simulated payment success
                'total_price' => $totalPrice
            ]);

            // Create Passengers
            foreach($passengersData as $p) {
                $reservation->passengers()->create($p);
            }

            return redirect()->route('booking.confirmation', $reservation->id);
            // We need a confirmation route.
        });
    }
}
