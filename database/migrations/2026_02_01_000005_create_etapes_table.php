<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etapes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->foreignId('gare_id')->constrained('gares')->onDelete('cascade');
            $table->integer('ordre'); // Sequence number in the route (1, 2, 3...)
            $table->time('heure_passage')->nullable(); // Relative time or expected time offset? Diagram says Time.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etapes');
    }
};
