<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create pivot table for many-to-many between segments and programmes
        Schema::create('programme_segment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained()->onDelete('cascade');
            $table->foreignId('segment_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Remove the direct foreign key from segments
        Schema::table('segments', function (Blueprint $table) {
            $table->dropForeign(['programme_id']);
            $table->dropColumn('programme_id');
        });
    }

    public function down(): void
    {
        Schema::table('segments', function (Blueprint $table) {
            $table->foreignId('programme_id')->nullable()->constrained();
        });

        Schema::dropIfExists('programme_segment');
    }
};
