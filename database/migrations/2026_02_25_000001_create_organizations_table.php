<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Organizations: SSG, FLP, Engineering Society, etc.
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Student Supreme Government"
            $table->string('short_name'); // e.g., "SSG"
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('color')->default('#004d00'); // Brand color
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};

