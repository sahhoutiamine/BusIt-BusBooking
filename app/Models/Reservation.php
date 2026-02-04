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
        'total_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }
    
    public function cancel()
    {
        $this->statut = 'Annulé';
        return $this->save();
    }
    
    public function getTickets()
    {
        return $this->passengers()->get()->map(function($p) {
            return "Ticket for " . $p->nom_complet;
        });
    }
}
