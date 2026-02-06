<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'reservation_id',
        'nom_complet',
        'cin',
        'type',
        'has_insurance',
        'has_snack_box',
        'prix_billet',
        'prix_options',
        'siege_numero',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
