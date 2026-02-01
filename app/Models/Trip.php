<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'programme_id',
        'ville_depart_id',
        'ville_arrivee_id',
        'date_depart',
        'heure_depart',
        'heure_arrivee',
        'distance',
        'tarif',
    ];

    protected $casts = [
        'date_depart' => 'date',
        'heure_depart' => 'datetime:H:i',
        'heure_arrivee' => 'datetime:H:i',
        'distance' => 'float',
        'tarif' => 'decimal:2',
    ];

    /**
     * Get the company that owns the trip.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the programme for this trip.
     */
    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    /**
     * Get the departure city.
     */
    public function villeDepart()
    {
        return $this->belongsTo(Ville::class, 'ville_depart_id');
    }

    /**
     * Get the arrival city.
     */
    public function villeArrivee()
    {
        return $this->belongsTo(Ville::class, 'ville_arrivee_id');
    }

    /**
     * Get the seats for this trip.
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Get the reservations for this trip.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Calculate the price based on distance.
     * This is a simplified calculation - adjust as needed.
     */
    public function calculatePrice(): float
    {
        // Example: 0.5 per km
        $pricePerKm = 0.5;
        return $this->distance * $pricePerKm;
    }

    /**
     * Get the tarif (price) for this trip.
     */
    public function getTarif(): float
    {
        return (float) $this->tarif;
    }

    /**
     * Get available seats for this trip.
     */
    public function getAvailableSeats()
    {
        return $this->seats()->where('is_available', true)->get();
    }

    /**
     * Get available seats count.
     */
    public function getAvailableSeatsCount(): int
    {
        return $this->seats()->where('is_available', true)->count();
    }
}
