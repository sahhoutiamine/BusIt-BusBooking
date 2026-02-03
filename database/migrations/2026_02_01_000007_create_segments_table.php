<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained('programmes')->onDelete('cascade');
            $table->foreignId('bus_id')->constrained('buses')->onDelete('cascade');
            
            // Inferring start/end gares from diagram's "Depart/Arrivee" arrows which pointed to Bus (likely typo for Gare)
            // or just representing the segment's endpoints relative to the route.
            // Linking to 'etapes' or 'gares'? 'gares' is safer.
            $table->foreignId('start_gare_id')->constrained('gares');
            $table->foreignId('end_gare_id')->constrained('gares');

            $table->decimal('tarif', 8, 2);
            $table->time('duree_estimee');
            $table->float('distance_km');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('segments');
    }
};
