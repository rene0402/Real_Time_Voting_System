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
        // Create or update the admin user
        try {
            $adminEmail = env('ADMIN_EMAIL', 'admin@cpsu.edu.ph');

            $admin = User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name'               => env('ADMIN_NAME', 'Admin'),
                    'password'           => Hash::make(env('ADMIN_PASSWORD', 'admin1900')),
                    'user_type'          => 'admin',
                    'email_verified_at'  => now(),
                ]
            );

            $action = $admin->wasRecentlyCreated ? 'Created' : 'Updated';
            $this->command->info("{$action} admin user: {$adminEmail}");
        } catch (\Throwable $e) {
            $this->command->error('Failed to seed admin user: ' . $e->getMessage());
        }

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
            OrganizationSeeder::class,
            ElectionSeeder::class,
            CandidateSeeder::class,
        ]);
    }
}
