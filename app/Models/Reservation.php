<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'seat_id',
        'date_reservation',
        'status',
        'siege_numero',
    ];

    protected $casts = [
        'date_reservation' => 'date',
    ];

    /**
     * Get the user that owns the reservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the trip for this reservation.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the seat for this reservation.
     */
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    /**
     * Cancel the reservation.
     */
    public function cancel()
    {
        // Release the seat
        if ($this->seat) {
            $this->seat->release();
        }

        // Delete the reservation
        $this->delete();
    }

    /**
     * Get seats information for this reservation.
     */
    public function getSeats()
    {
        return $this->seat;
    }

    /**
     * Mark reservation as paid.
     */
    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->save();
    }

    /**
     * Mark reservation as confirmed.
     */
    public function confirm()
    {
        $this->status = 'confirmed';
        $this->save();
    }

    /**
     * Mark reservation as cancelled.
     */
    public function cancelReservation()
    {
        $this->status = 'cancelled';
        if ($this->seat) {
            $this->seat->release();
        }
        $this->save();
    }

    /**
     * Check if reservation is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if reservation is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
