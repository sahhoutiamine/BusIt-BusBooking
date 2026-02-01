<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'nom',
    ];

    /**
     * Get programmes departing from this city.
     */
    public function programmesDeparture()
    {
        return $this->hasMany(Programme::class, 'ville_depart_id');
    }

    /**
     * Get programmes arriving to this city.
     */
    public function programmesArrival()
    {
        return $this->hasMany(Programme::class, 'ville_arrivee_id');
    }

    /**
     * Get trips departing from this city.
     */
    public function tripsDeparture()
    {
        return $this->hasMany(Trip::class, 'ville_depart_id');
    }

    /**
     * Get trips arriving to this city.
     */
    public function tripsArrival()
    {
        return $this->hasMany(Trip::class, 'ville_arrivee_id');
    }

    /**
     * Get all villes (static method as per class diagram).
     */
    public static function getVilles()
    {
        return self::all();
    }
}
