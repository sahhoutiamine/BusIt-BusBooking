<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    protected $fillable = ['route_id', 'jour_depart', 'heure_depart', 'heure_arrivee', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function segments()
    {
        return $this->belongsToMany(Segment::class, 'programme_segment')->withTimestamps();
    }
    
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
