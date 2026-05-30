<?php

namespace Tests\Feature\Auth;

use App\Models\Registration;
use App\Models\User;
use App\Services\UserOnboardingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_onboarding_is_idempotent_for_existing_user(): void
    {
        $user = User::create([
            'name' => 'Existing Rider',
            'email' => 'existing-rider@example.com',
            'password' => 'password',
        ]);

        $service = app(UserOnboardingService::class);
        $service->createForUser($user);
        $service->createForUser($user->fresh());

        $registration = Registration::where('user_id', $user->id)->firstOrFail();

        $this->assertDatabaseCount('registrations', 1);
        $this->assertSame(1, $registration->invoices()->where('type', 'deposit')->count());
        $this->assertSame(1, $registration->invoices()->where('type', 'final')->count());
        $this->assertSame(6, $registration->checklistItems()->count());
        $this->assertSame(1, $registration->motorcycles()->count());
        $this->assertSame(1, $registration->tireRequest()->count());
        $this->assertSame(1, $registration->travelInfo()->count());
    }

    public function test_dashboard_renders_onboarding_cta_for_new_user_without_registration(): void
    {
        $this->post('/register', [
            'name' => 'Dashboard Rider',
            'email' => 'dashboard-rider@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Je registratie is nog niet compleet')
            ->assertSee('Registratie afronden');
    }

    public function test_dashboard_shows_full_state_after_onboarding_data_is_created(): void
    {
        $user = User::create([
            'name' => 'Onboarded Rider',
            'email' => 'onboarded-rider@example.com',
            'password' => 'password',
        ]);

        app(UserOnboardingService::class)->createForUser($user);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSee('Mijn Speedweek')
            ->assertSee('Finance')
            ->assertSee('Open checklist')
            ->assertDontSee('Je registratie is nog niet compleet');
    }
}
