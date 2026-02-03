<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = ['matricule', 'capacite', 'statut'];

    public function checkAvailability()
    {
        return $this->statut === 'active';
    }
}
