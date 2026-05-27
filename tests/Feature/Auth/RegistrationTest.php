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

    public function test_new_registered_user_gets_dashboard_data_without_seeders(): void
    {
        $this->post('/register', [
            'name' => 'Production Rider',
            'email' => 'production-rider@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'production-rider@example.com')->firstOrFail();
        $registration = $user->registration()->with(['event', 'package', 'motorcycle', 'tireRequest', 'travelInfo', 'checklistItems', 'invoices'])->first();

        $this->assertNotNull($registration);
        $this->assertSame('Speedweek 2026', $registration->event->name);
        $this->assertSame('Full Package', $registration->package->name);
        $this->assertSame('Yamaha', $registration->motorcycle->brand);
        $this->assertSame('Pirelli', $registration->tireRequest->preferred_brand);
        $this->assertSame('HV0001', $registration->travelInfo->outbound_flight_number);
        $this->assertCount(6, $registration->checklistItems);
        $this->assertCount(2, $registration->invoices);
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

    public function test_dashboard_no_longer_shows_empty_registration_state_after_normal_registration(): void
    {
        $this->post('/register', [
            'name' => 'Dashboard Rider',
            'email' => 'dashboard-rider@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->get('/dashboard')
            ->assertOk()
            ->assertDontSee('Nog geen registratie.')
            ->assertSee('Finance')
            ->assertSee('Open checklist')
            ->assertSee('Mijn motor')
            ->assertSee('Yamaha');
    }

    public function test_dashboard_renders_onboarding_cta_when_registration_data_is_missing(): void
    {
        $user = User::create([
            'name' => 'Empty Rider',
            'email' => 'empty-rider@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertDontSee('Nog geen registratie.')
            ->assertSee('Je registratie is nog niet compleet')
            ->assertSee('Registratie afronden');
    }
}
