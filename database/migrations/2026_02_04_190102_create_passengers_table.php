<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->string('nom_complet');
            $table->string('cin')->nullable(); // Required for adults
            $table->string('type')->default('adulte'); // adulte, enfant
            $table->boolean('has_insurance')->default(false);
            $table->boolean('has_snack_box')->default(false);
            $table->decimal('prix_billet', 8, 2);
            $table->decimal('prix_options', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
