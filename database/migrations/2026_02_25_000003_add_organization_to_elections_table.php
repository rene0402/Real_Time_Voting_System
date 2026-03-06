<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add organization_id to elections table
     */
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null');
            $table->text('voter_eligibility')->nullable(); // JSON string for eligibility rules
            $table->string('voting_method')->default('fptp'); // fptp, ranked_choice, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn(['organization_id', 'voter_eligibility', 'voting_method']);
        });
    }
};

