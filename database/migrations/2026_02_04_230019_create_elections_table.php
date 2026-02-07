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
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['single', 'multi', 'referendum']);
            $table->enum('status', ['scheduled', 'active', 'closed', 'paused'])->default('scheduled');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->text('description')->nullable();
            $table->integer('total_votes')->default(0);
            $table->boolean('is_paused')->default(false);
            $table->boolean('results_locked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
