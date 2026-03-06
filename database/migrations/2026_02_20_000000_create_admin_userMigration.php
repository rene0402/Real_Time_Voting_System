<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates a default admin user for the voting system.
     */
    public function up(): void
    {
        DB::table('users')->upsert([
            'name' => 'Ray Agoncillo',
            'email' => 'rayagoncillo797@gmail.com',
            'password' => Hash::make('agoncillo092020'),
            'user_type' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ], ['email'], [
            'name' => 'Ray Agoncillo',
            'password' => Hash::make('agoncillo092020'),
            'user_type' => 'admin',
            'email_verified_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', 'rayagoncillo797@gmail.com')->delete();
    }
};

