<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id', 
        'start_gare_id', 
        'end_gare_id', 
        'tarif', 
        'duree_estimee', 
        'distance_km'
    ];

    public function programmes()
    {
        return $this->belongsToMany(Programme::class, 'programme_segment')->withTimestamps();
    }
    
    public function getProgrammeAttribute()
    {
        return $this->programmes->first();
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
    
    public function startGare()
    {
        return $this->belongsTo(Gare::class, 'start_gare_id');
    }
    
    public function endGare()
    {
        return $this->belongsTo(Gare::class, 'end_gare_id');
    }

    public function calculerPrix()
    {
        return $this->tarif;
    }
}
