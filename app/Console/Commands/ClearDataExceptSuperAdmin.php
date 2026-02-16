<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearDataExceptSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-data-except-super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all database data except the super admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->confirm('This will delete all data except the super admin user. Are you sure?')) {
            return;
        }

        $this->info('Starting data clearance...');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Get all table names
        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        // Tables to exclude from truncation
        $excludeTables = ['migrations']; // Keep migrations table

        foreach ($tables as $table) {
            if (in_array($table, $excludeTables)) {
                continue;
            }

            if ($table === 'users') {
                // For users table, delete all except super admin
                DB::table('users')->where('email', '!=', 'ramy@pura.com')->delete();
                $this->info("Cleared users table except super admin");
            } else {
                // Truncate other tables
                DB::table($table)->truncate();
                $this->info("Truncated table: {$table}");
            }
        }

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Data clearance completed successfully!');
        $this->info('Super admin user (ramy@pura.com) has been preserved.');
    }
}
