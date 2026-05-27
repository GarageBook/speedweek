<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public const NAME = 'Willem van Veelen';
    public const EMAIL = 'willemvanveelen@icloud.com';
    public const PASSWORD = 'Speedweek1!';

    public function run(): void
    {
        self::upsertAdminUser();
    }

    public static function upsertAdminUser(?string $email = null, ?string $password = null, ?string $name = null): User
    {
        return User::updateOrCreate(
            ['email' => Str::lower($email ?: self::EMAIL)],
            [
                'name' => $name ?: self::NAME,
                'password' => Hash::make($password ?: self::PASSWORD),
                'is_admin' => true,
            ]
        );
    }
}
