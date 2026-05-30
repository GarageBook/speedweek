<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('track_lap_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('session_number');
            $table->unsignedInteger('best_lap_ms');
            $table->timestamps();
            $table->unique(['registration_id', 'session_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('track_lap_results');
    }
};
