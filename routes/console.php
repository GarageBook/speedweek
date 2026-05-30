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
    $sqlitePath = (string) config('database.connections.sqlite.database');
    $sqliteExists = $sqlitePath !== '' && file_exists($sqlitePath);
    $sqliteInode = $sqliteExists ? fileinode($sqlitePath) : null;

    $state = [
        'timestamp' => now()->toIso8601String(),
        'db_connection' => (string) env('DB_CONNECTION', 'not-set'),
        'db_database' => (string) env('DB_DATABASE', 'not-set'),
        'resolved_sqlite_path' => $sqlitePath,
        'sqlite_file_exists' => $sqliteExists ? 'yes' : 'no',
        'sqlite_inode' => $sqliteInode ?: 'n/a',
        'session_driver' => (string) env('SESSION_DRIVER', 'not-set'),
        'session_lifetime' => (string) env('SESSION_LIFETIME', 'not-set'),
        'user_count' => User::count(),
    ];

    $this->info('=== OPS DEBUG STATE ===');
    foreach ($state as $key => $value) {
        $this->line($key.': '.$value);
    }
    $this->info('=== END OPS DEBUG STATE ===');

    Log::info('OPS DEBUG STATE', $state);

    return self::SUCCESS;
})->purpose('Temporary production debug output for DB/session persistence checks.');
