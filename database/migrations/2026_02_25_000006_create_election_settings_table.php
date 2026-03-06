<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Election-specific settings and voting rules
     */
    public function up(): void
    {
        Schema::create('election_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->boolean('allow_write_in')->default(false); // Allow write-in candidates
            $table->boolean('show_results_live')->default(true); // Show live results
            $table->integer('max_votes_per_voter')->default(1); // Max votes a voter can cast
            $table->boolean('require_2fa')->default(false); // Require two-factor authentication
            $table->boolean('allow_absentee')->default(false); // Allow early voting
            $table->text('custom_rules')->nullable(); // Any additional rules
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('election_settings');
    }
};

