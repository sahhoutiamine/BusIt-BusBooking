<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Client
            $table->foreignId('segment_id')->constrained('segments')->onDelete('cascade');
            $table->date('date_reservation');
            $table->string('statut')->default('confirmé'); // Confirmé, Annulé, Payé
            $table->integer('siege_numero');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
