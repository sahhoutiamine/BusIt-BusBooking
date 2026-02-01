<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'matricule',
        'status',
    ];

    /**
     * Get the trips for the company.
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Check availability of the company.
     */
    public function checkAvailability(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get active companies.
     */
    public static function getActiveCompanies()
    {
        return self::where('status', 'active')->get();
    }
}
