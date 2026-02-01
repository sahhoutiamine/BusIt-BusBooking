<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'ville_depart_id',
        'ville_arrivee_id',
        'heure_depart',
        'heure_arrivee',
        'is_active',
    ];

    protected $casts = [
        'heure_depart' => 'datetime:H:i',
        'heure_arrivee' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

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
     * Get the trips for this programme.
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Check if the programme is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activate the programme.
     */
    public function activate()
    {
        $this->is_active = true;
        $this->save();
    }

    /**
     * Deactivate the programme.
     */
    public function deactivate()
    {
        $this->is_active = false;
        $this->save();
    }
}
