<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('users:ensure-admin {--email=} {--password=} {--name=}', function () {
    $email = Str::lower($this->option('email') ?: env('ADMIN_EMAIL', 'admin@speedweek.local'));
    $password = $this->option('password') ?: env('ADMIN_PASSWORD', 'password');
    $name = $this->option('name') ?: env('ADMIN_NAME', 'Speedweek Admin');

    if (! $email || ! $password) {
        $this->error('Admin email and password are required.');

        return self::FAILURE;
    }

    $user = User::updateOrCreate(
        ['email' => $email],
        [
            'name' => $name,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]
    );

    $this->info(($user->wasRecentlyCreated ? 'Created' : 'Updated').' admin user '.$email.'.');

    return self::SUCCESS;
})->purpose('Create or update the configured admin user.');
