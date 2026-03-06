<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Positions: President, Treasurer, Secretary, etc.
     */
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "President", "Secretary"
            $table->text('description')->nullable();
            $table->integer('order')->default(0); // Display order on ballot
            $table->integer('seats_available')->default(1); // Number of seats (e.g., 5 for Senators)
            $table->enum('election_type', ['single_winner', 'multi_winner'])->default('single_winner');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};

