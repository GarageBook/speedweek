<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersistentLoginBehaviorTest extends TestCase
{
    use RefreshDatabase;

    public function test_remember_login_persists_authentication(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull(User::whereKey($user->id)->value('remember_token'));
    }

    public function test_admin_can_switch_between_admin_and_dashboard_without_logout(): void
    {
        $this->seed(AdminUserSeeder::class);
        $admin = User::where('email', AdminUserSeeder::EMAIL)->firstOrFail();

        $this->post('/login', [
            'email' => $admin->email,
            'password' => AdminUserSeeder::PASSWORD,
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->get('/dashboard')->assertOk();
        $this->assertAuthenticatedAs($admin);

        $this->get('/admin')->assertOk();
        $this->assertAuthenticatedAs($admin);

        $this->get('/dashboard')->assertOk();
        $this->assertAuthenticatedAs($admin);
    }

    public function test_session_persists_after_browser_restart_simulation(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);

        // Simulate loss of active session while keeping remember-based auth recovery.
        $this->flushSession();

        $this->get('/dashboard')->assertOk();
        $this->assertAuthenticatedAs($user);
    }
}
