<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Voter eligibility rules per election
     */
    public function up(): void
    {
        Schema::create('voter_eligibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->enum('eligibility_type', ['email_domain', 'student_ids', 'manual', 'all_members']);
            $table->text('allowed_domains')->nullable(); // JSON: ["@ub.edu.ph", "@cpsu.edu.ph"]
            $table->text('allowed_student_ids')->nullable(); // JSON: Array of student IDs
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade'); // For organization members only
            $table->boolean('require_verification')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voter_eligibility');
    }
};

