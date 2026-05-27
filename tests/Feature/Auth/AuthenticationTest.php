<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_admin_user_can_be_bootstrapped_for_production_login(): void
    {
        Artisan::call('users:ensure-admin', [
            '--email' => 'admin@speedweek.local',
            '--password' => 'password',
            '--name' => 'Speedweek Admin',
        ]);

        $admin = User::where('email', 'admin@speedweek.local')->firstOrFail();

        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('password', $admin->password));

        $this->post('/login', [
            'email' => 'admin@speedweek.local',
            'password' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_bootstrap_is_idempotent(): void
    {
        Artisan::call('users:ensure-admin', ['--email' => 'admin@speedweek.local', '--password' => 'password']);
        Artisan::call('users:ensure-admin', ['--email' => 'admin@speedweek.local', '--password' => 'password']);

        $this->assertSame(1, User::where('email', 'admin@speedweek.local')->count());
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
