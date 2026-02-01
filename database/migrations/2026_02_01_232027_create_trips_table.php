<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('programme_id')->constrained('programmes')->onDelete('cascade');
            $table->foreignId('ville_depart_id')->constrained('villes')->onDelete('cascade');
            $table->foreignId('ville_arrivee_id')->constrained('villes')->onDelete('cascade');
            $table->date('date_depart');
            $table->time('heure_depart');
            $table->time('heure_arrivee');
            $table->float('distance');
            $table->decimal('tarif', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
