<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'segment_id', 
        'date_reservation', 
        'statut', 
        'siege_numero'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }
    
    public function cancel()
    {
        $this->statut = 'Annulé';
        return $this->save();
    }
    
    public function getTicket()
    {
        // Logic to retrieve ticket details
        return "Ticket for Seat " . $this->siege_numero;
    }
}
