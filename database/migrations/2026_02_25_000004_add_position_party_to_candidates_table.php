<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add position_id and party_affiliation to candidates table
     */
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->constrained()->onDelete('set null');
            $table->string('party_affiliation')->nullable(); // e.g., "United Front", "Student First"
            $table->string('manifesto')->nullable(); // Campaign platform/summary
            $table->integer('order')->default(0); // Display order within position
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn(['position_id', 'party_affiliation', 'manifesto', 'order']);
        });
    }
};

