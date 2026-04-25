<?php

/**
 * Standalone admin account bootstrap script.
 *
 * Boots the Laravel application and creates (or updates) the admin user
 * using the ADMIN_EMAIL and ADMIN_PASSWORD environment variables.
 *
 * Usage:
 *   php bootstrap/create-admin.php
 *
 * This script is designed to be chained in the Railway start command:
 *   php bootstrap/create-admin.php && php -S 0.0.0.0:$PORT -t public
 *
 * It exits with code 0 on success and code 1 on failure so the &&
 * operator can gate the web server from starting if something is wrong.
 */

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/app.php';

// Boot the HTTP kernel so all service providers are registered and the
// database connection is available.
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$email    = env('ADMIN_EMAIL', 'admin@cpsu.edu.ph');
$password = env('ADMIN_PASSWORD', 'admin1900');
$name     = env('ADMIN_NAME', 'Admin');

echo "[create-admin] Upserting admin account: {$email}" . PHP_EOL;

try {
    $user = App\Models\User::updateOrCreate(
        ['email' => $email],
        [
            'name'              => $name,
            'password'          => Illuminate\Support\Facades\Hash::make($password),
            'user_type'         => 'admin',
            'email_verified_at' => now(),
        ]
    );

    $action = $user->wasRecentlyCreated ? 'created' : 'updated';
    echo "[create-admin] Admin account {$action} successfully." . PHP_EOL;

    exit(0);
} catch (Throwable $e) {
    echo "[create-admin] ERROR: Failed to upsert admin account — " . $e->getMessage() . PHP_EOL;

    // Exit with a non-zero code so the start command chain is halted and
    // Railway surfaces the failure clearly in the deploy logs.
    exit(1);
}
