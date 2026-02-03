<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->string('jour_depart'); // E.g., 'Monday', '2023-10-27'. Diagram says String.
            $table->time('heure_depart');
            $table->time('heure_arrivee');
            $table->boolean('is_active')->default(true); // From method isActive()
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
