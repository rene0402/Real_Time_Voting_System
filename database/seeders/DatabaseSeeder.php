<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create super admin user
        User::updateOrCreate(
            ['email' => 'ramy@pura.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
            ]
        );

        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
            ]
        );

        // Create test voters with different statuses
        User::updateOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Smith',
                'password' => Hash::make('password'),
                'user_type' => 'voter',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'jane@example.com'],
            [
                'name' => 'Jane Doe',
                'password' => Hash::make('password'),
                'user_type' => 'voter',
                'email_verified_at' => null, // Pending verification
            ]
        );

        User::updateOrCreate(
            ['email' => 'bob@example.com'],
            [
                'name' => 'Bob Wilson',
                'password' => Hash::make('password'),
                'user_type' => 'voter',
                'email_verified_at' => now(),
                'blocked_at' => now(), // Blocked voter
            ]
        );

        User::updateOrCreate(
            ['email' => 'alice@example.com'],
            [
                'name' => 'Alice Johnson',
                'password' => Hash::make('password'),
                'user_type' => 'voter',
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(2),
            ]
        );

        User::updateOrCreate(
            ['email' => 'charlie@example.com'],
            [
                'name' => 'Charlie Brown',
                'password' => Hash::make('password'),
                'user_type' => 'voter',
                'email_verified_at' => null,
            ]
        );

        $this->call([
            ElectionSeeder::class,
            CandidateSeeder::class,
        ]);
    }
}
