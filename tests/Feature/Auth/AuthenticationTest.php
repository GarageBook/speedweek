<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
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

    public function test_permanent_admin_user_can_be_seeded_and_login(): void
    {
        $this->seed(AdminUserSeeder::class);

        $admin = User::where('email', AdminUserSeeder::EMAIL)->firstOrFail();

        $this->assertSame(AdminUserSeeder::NAME, $admin->name);
        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check(AdminUserSeeder::PASSWORD, $admin->password));

        $this->post('/login', [
            'email' => AdminUserSeeder::EMAIL,
            'password' => AdminUserSeeder::PASSWORD,
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_permanent_admin_user_has_access_to_admin_panel(): void
    {
        $this->seed(AdminUserSeeder::class);
        $admin = User::where('email', AdminUserSeeder::EMAIL)->firstOrFail();

        $this->actingAs($admin)->get('/admin')
            ->assertOk()
            ->assertSee('Speedweek Admin')
            ->assertSee('Users');
    }

    public function test_regular_user_does_not_have_admin_panel_access(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_existing_permanent_admin_user_is_updated_by_seeder(): void
    {
        User::create([
            'name' => 'Old Name',
            'email' => AdminUserSeeder::EMAIL,
            'password' => Hash::make('old-password'),
            'is_admin' => false,
        ]);

        $this->seed(AdminUserSeeder::class);

        $admin = User::where('email', AdminUserSeeder::EMAIL)->firstOrFail();

        $this->assertSame(AdminUserSeeder::NAME, $admin->name);
        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check(AdminUserSeeder::PASSWORD, $admin->password));
    }

    public function test_admin_seeder_and_command_are_idempotent(): void
    {
        $this->seed(AdminUserSeeder::class);
        $this->seed(AdminUserSeeder::class);
        Artisan::call('users:ensure-admin');

        $this->assertSame(1, User::where('email', AdminUserSeeder::EMAIL)->count());
    }

    public function test_new_regular_users_are_not_admins(): void
    {
        $this->post('/register', [
            'name' => 'Regular User',
            'email' => 'regular-user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'regular-user@example.com')->firstOrFail();

        $this->assertFalse($user->is_admin);
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
