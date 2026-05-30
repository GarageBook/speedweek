<?php

use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

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
