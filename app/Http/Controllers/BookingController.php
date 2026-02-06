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
    public function index()
    {
        $reservations = auth()->user()->reservations()
            ->with(['segment.startGare.ville', 'segment.endGare.ville', 'passengers'])
            ->orderBy('date_reservation', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('booking.index', compact('reservations'));
    }

    public function confirmation(Reservation $reservation)
    {
        $reservation->load(['segment.programmes', 'segment.bus', 'segment.startGare.ville', 'segment.endGare.ville', 'passengers']);
        return view('booking.confirmation', compact('reservation'));
    }

    public function ticket(Reservation $reservation)
    {
        $reservation->load(['segment.programmes', 'segment.bus', 'segment.startGare.ville', 'segment.endGare.ville', 'passengers']);
        return view('booking.ticket', compact('reservation'));
    }
    public function create(Request $request, Segment $segment)
    {
        $dateStr = $request->query('date') ?? now()->format('Y-m-d');
        $date = Carbon::parse($dateStr);
        $passengersCount = (int) $request->query('passengers_count', 1);

        $daysMap = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche',
        ];
        $dayName = $daysMap[$date->format('l')] ?? $date->format('l');

        $segment->load(['programmes' => function($q) use ($dayName) {
            $q->where('jour_depart', $dayName);
        }, 'bus', 'startGare.ville', 'endGare.ville']);

        if (!$segment->programme) {
             return redirect()->route('search.index')->with('error', 'This trip is not available on the selected date.');
        }

        // Calculate available seats
        // Count passengers in active reservations for this segment/date
        $occupiedQuery = Passenger::whereHas('reservation', function($q) use ($segment, $date) {
             $q->where('segment_id', $segment->id)
               ->where('date_reservation', $date->format('Y-m-d'))
               ->where('statut', '!=', 'Annulé');
        });
        
        $occupiedCount = (clone $occupiedQuery)->count();
        $occupiedSeats = (clone $occupiedQuery)->pluck('siege_numero')->filter()->values()->toArray();
        
        $available = $segment->bus->capacite - $occupiedCount;

        return view('booking.create', compact('segment', 'date', 'available', 'passengersCount', 'occupiedSeats'));
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
            'passengers.*.siege_numero' => 'required|integer|min:1',
        ]);

        return DB::transaction(function() use ($request) {
            $segment = Segment::findOrFail($request->segment_id);
            $basePrice = $segment->tarif;
            
            // Seat selection validation
            $inputSeats = collect($request->passengers)->pluck('siege_numero')->toArray();
            if (count($inputSeats) !== count(array_unique($inputSeats))) {
                 return back()->withErrors(['passengers' => 'Each passenger must have a unique seat.'])->withInput();
            }

            $alreadyTaken = Passenger::whereHas('reservation', function($q) use ($request) {
                $q->where('segment_id', $request->segment_id)
                  ->where('date_reservation', $request->date_reservation)
                  ->where('statut', '!=', 'Annulé');
            })->whereIn('siege_numero', $inputSeats)->exists();

            if ($alreadyTaken) {
                return back()->withErrors(['passengers' => 'One or more selected seats were just taken by another traveler. Please choose different seats.'])->withInput();
            }

            // Calculate totals
            $totalPrice = 0;
            $passengersData = [];

            foreach($request->passengers as $pData) {
                $pPrice = $basePrice; 
                $optionsPrice = 0;
                if(!empty($pData['has_insurance'])) {
                    $optionsPrice += 25;
                }
                if(!empty($pData['has_snack_box'])) {
                    $optionsPrice += 15;
                }
                
                $totalPrice += ($pPrice + $optionsPrice);
                
                $passengersData[] = [
                    'nom_complet' => $pData['nom_complet'],
                    'cin' => $pData['cin'] ?? null,
                    'type' => $pData['type'],
                    'has_insurance' => !empty($pData['has_insurance']),
                    'has_snack_box' => !empty($pData['has_snack_box']),
                    'prix_billet' => $pPrice,
                    'prix_options' => $optionsPrice,
                    'siege_numero' => $pData['siege_numero'],
                ];
            }
            
            // Create Reservation
            $reservation = Reservation::create([
                'user_id' => auth()->id(),
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
        });
    }
}
