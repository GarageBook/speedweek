<?php

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('users:ensure-admin {--email=} {--password=} {--name=}', function () {
    $email = $this->option('email') ?: env('ADMIN_EMAIL', AdminUserSeeder::EMAIL);
    $password = $this->option('password') ?: env('ADMIN_PASSWORD', AdminUserSeeder::PASSWORD);
    $name = $this->option('name') ?: env('ADMIN_NAME', AdminUserSeeder::NAME);

    if (! $email || ! $password) {
        $this->error('Admin email and password are required.');

        return self::FAILURE;
    }

    $user = AdminUserSeeder::upsertAdminUser($email, $password, $name);

    $this->info(($user->wasRecentlyCreated ? 'Created' : 'Updated').' admin user '.$user->email.'.');

    return self::SUCCESS;
})->purpose('Create or update the configured admin user.');

Artisan::command('ops:debug-state', function () {
    $defaultConnection = (string) config('database.default');
    $sqlitePath = (string) config('database.connections.sqlite.database');
    $sqliteExists = $sqlitePath !== '' && file_exists($sqlitePath);

    $state = [
        'timestamp' => now()->toIso8601String(),
        'db_connection' => $defaultConnection,
        'db_database' => (string) env('DB_DATABASE', 'not-set'),
        'resolved_sqlite_path' => $sqlitePath,
        'session_driver' => (string) config('session.driver'),
        'session_lifetime' => (int) config('session.lifetime'),
        'sqlite_exists' => $sqliteExists ? 'yes' : 'no',
        'user_count' => User::count(),
    ];

    $this->info('=== OPS DEBUG STATE ===');
    foreach ($state as $key => $value) {
        $this->line($key.': '.$value);
    }
    $this->info('=== END OPS DEBUG STATE ===');

    Log::info('OPS DEBUG STATE', $state);

    return self::SUCCESS;
})->purpose('Temporary production startup diagnostics for persistence and sessions.');

Artisan::command('ops:assert-production-persistence {--force-production}', function () {
    $isProduction = app()->environment('production') || (bool) $this->option('force-production');

    if (! $isProduction) {
        $this->warn('Skipping production persistence assertion outside production environment.');

        return self::SUCCESS;
    }

    $errors = [];
    $defaultConnection = (string) config('database.default');
    $allowedConnections = ['sqlite', 'mysql', 'pgsql', 'mariadb'];

    if (! in_array($defaultConnection, $allowedConnections, true)) {
        $errors[] = "DB_CONNECTION must be one of [".implode(', ', $allowedConnections)."], got '{$defaultConnection}'.";
    }

    if ($defaultConnection === 'sqlite') {
        $sqlitePath = (string) config('database.connections.sqlite.database');
        if ($sqlitePath !== '/var/data/database.sqlite') {
            $errors[] = "DB_DATABASE must resolve to /var/data/database.sqlite for sqlite production, got '{$sqlitePath}'.";
        }
    }

    $sessionDriver = (string) config('session.driver');
    if ($sessionDriver !== 'database') {
        $errors[] = "SESSION_DRIVER must be 'database', got '{$sessionDriver}'.";
    }

    $sessionLifetime = (int) config('session.lifetime');
    if ($sessionLifetime !== 43200) {
        $errors[] = "SESSION_LIFETIME must be 43200, got '{$sessionLifetime}'.";
    }

    $expireOnClose = (bool) config('session.expire_on_close');
    if ($expireOnClose !== false) {
        $errors[] = 'SESSION_EXPIRE_ON_CLOSE must be false.';
    }

    if ($errors !== []) {
        foreach ($errors as $error) {
            $message = 'FATAL PRODUCTION PERSISTENCE GUARD: '.$error;
            $this->error($message);
            Log::error($message);
        }

        return self::FAILURE;
    }

    $this->info('Production persistence guard passed.');

    return self::SUCCESS;
})->purpose('Fail-fast guard: block production startup when persistence/session config is unsafe.');
