<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'name',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Get the trip that owns the seat.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the reservations for this seat.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get availability status.
     */
    public function getAvailability(): bool
    {
        return $this->is_available;
    }

    /**
     * Mark seat as booked.
     */
    public function book()
    {
        $this->is_available = false;
        $this->save();
    }

    /**
     * Mark seat as available.
     */
    public function release()
    {
        $this->is_available = true;
        $this->save();
    }

    /**
     * Check if seat is available.
     */
    public function isAvailable(): bool
    {
        return $this->is_available;
    }
}
