<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAdminName extends Command
{
    protected $signature = 'admin:update-name {name : The name to set for the admin user}';

    protected $description = 'Update the admin user name';

    public function handle(): int
    {
        $name = $this->argument('name');

        $updated = DB::table('users')
            ->where('email', 'rayagoncillo797@gmail.com')
            ->update(['name' => $name, 'updated_at' => now()]);

        if ($updated) {
            $this->info("Admin name updated successfully to: {$name}");
            return Command::SUCCESS;
        }

        $this->error('Admin user not found!');
        return Command::FAILURE;
    }
}

