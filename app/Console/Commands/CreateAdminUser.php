<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update the admin user from environment variables (ADMIN_EMAIL, ADMIN_PASSWORD)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email    = env('ADMIN_EMAIL', 'admin@cpsu.edu.ph');
        $password = env('ADMIN_PASSWORD', 'admin1900');
        $name     = env('ADMIN_NAME', 'Admin');

        $this->info("Creating/updating admin user: {$email}");

        try {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'              => $name,
                    'password'          => Hash::make($password),
                    'user_type'         => 'admin',
                    'email_verified_at' => now(),
                ]
            );

            if ($user->wasRecentlyCreated) {
                $this->info("Admin user created successfully: {$email}");
            } else {
                $this->info("Admin user updated successfully: {$email}");
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Failed to create/update admin user: {$e->getMessage()}");

            return Command::FAILURE;
        }
    }
}
