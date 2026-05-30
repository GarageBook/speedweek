<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Package;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingMotorcycleDetailsTest extends TestCase
{
    use RefreshDatabase;

    private function eventWithPackage(): array
    {
        $event = Event::create([
            'name' => 'Speedweek 2026',
            'slug' => 'speedweek-2026',
            'starts_at' => '2026-11-25',
            'ends_at' => '2026-12-02',
            'location_name' => 'Almeria',
            'circuit_name' => 'Circuito de Almeria',
            'status' => 'open',
        ]);

        $package = Package::create([
            'event_id' => $event->id,
            'name' => 'Independent',
            'slug' => 'independent',
            'price_cents' => 100000,
            'deposit_percentage' => 30,
            'is_active' => true,
        ]);

        return [$event, $package];
    }

    public function test_onboarding_can_create_motorcycle_with_required_brand_only(): void
    {
        [$event, $package] = $this->eventWithPackage();
        $user = User::create(['name' => 'Brand Only', 'email' => 'brand-only@example.com', 'password' => 'password']);

        $this->actingAs($user)->post(route('events.register.store', $event), [
            'package_id' => $package->id,
            'motorcycle_brand' => 'Honda',
            'has_license_plate' => '0',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('motorcycles', [
            'user_id' => $user->id,
            'brand' => 'Honda',
            'model' => 'Onbekend',
            'license_plate' => null,
        ]);
    }

    public function test_onboarding_can_store_optional_motorcycle_fields(): void
    {
        [$event, $package] = $this->eventWithPackage();
        $user = User::create(['name' => 'Optional Fields', 'email' => 'optional-fields@example.com', 'password' => 'password']);

        $this->actingAs($user)->post(route('events.register.store', $event), [
            'package_id' => $package->id,
            'motorcycle_brand' => 'Yamaha',
            'motorcycle_model' => 'R1M',
            'motorcycle_year' => 2024,
            'has_license_plate' => '1',
            'motorcycle_license_plate' => 'AB-12-CD',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('motorcycles', [
            'user_id' => $user->id,
            'brand' => 'Yamaha',
            'model' => 'R1M',
            'year' => 2024,
            'license_plate' => 'AB-12-CD',
        ]);
    }

    public function test_onboarding_can_store_notes_in_bijzonderheden(): void
    {
        [$event, $package] = $this->eventWithPackage();
        $user = User::create(['name' => 'Notes Rider', 'email' => 'notes-rider@example.com', 'password' => 'password']);

        $this->actingAs($user)->post(route('events.register.store', $event), [
            'package_id' => $package->id,
            'motorcycle_brand' => 'Suzuki',
            'has_license_plate' => '0',
            'motorcycle_notes' => "Race fairings\nQuickshifter installed",
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('motorcycles', [
            'user_id' => $user->id,
            'brand' => 'Suzuki',
            'notes' => "Race fairings\nQuickshifter installed",
        ]);
    }

    public function test_existing_users_are_not_affected(): void
    {
        [$event, $package] = $this->eventWithPackage();
        $user = User::create(['name' => 'Existing Rider', 'email' => 'existing-rider-2@example.com', 'password' => 'password']);

        $registration = Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'package_id' => $package->id,
            'status' => 'pending',
            'payment_status' => 'deposit_invoiced',
            'total_amount_cents' => 100000,
            'deposit_amount_cents' => 30000,
        ]);

        $registration->motorcycle()->create([
            'user_id' => $user->id,
            'brand' => 'Ducati',
            'model' => 'V4',
            'year' => 2023,
            'license_plate' => 'EX-IST-1',
        ]);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSee('Mijn Speedweek')
            ->assertDontSee('Je registratie is nog niet compleet');

        $this->assertDatabaseHas('motorcycles', [
            'user_id' => $user->id,
            'brand' => 'Ducati',
            'model' => 'V4',
        ]);
    }
}
