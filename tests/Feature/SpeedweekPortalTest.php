<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Package;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpeedweekPortalTest extends TestCase
{
    use RefreshDatabase;

    private function eventWithPackages(): array
    {
        $event = Event::create(['name'=>'Speedweek 2026','slug'=>'speedweek-2026','starts_at'=>'2026-11-25','ends_at'=>'2026-12-02','location_name'=>'Almería','circuit_name'=>'Circuito de Almería','status'=>'open']);
        $rider = Package::create(['event_id'=>$event->id,'name'=>'Independent','slug'=>'independent','price_cents'=>100000,'deposit_percentage'=>30,'is_active'=>true]);
        $spectator = Package::create(['event_id'=>$event->id,'name'=>'Spectator','slug'=>'spectator','price_cents'=>50000,'deposit_percentage'=>30,'is_active'=>true]);
        return [$event, $rider, $spectator];
    }



    public function test_homepage_register_button_starts_account_registration_for_event(): void
    {
        [$event] = $this->eventWithPackages();

        $this->get('/')
            ->assertOk()
            ->assertSee('/register?event='.$event->slug, false)
            ->assertSee('Registreer');
    }



    public function test_homepage_still_shows_register_button_without_event(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Registreer')
            ->assertSee('/register', false)
            ->assertDontSee('Racetrack deelnemersportaal voor Zuid-Spanje.')
            ->assertDontSee('>Speedweek</h1>', false);
    }

    public function test_form_actions_use_forwarded_https_scheme(): void
    {
        config(['app.url' => 'https://speedweek.bergmolen.nl']);

        $this->withServerVariables([
            'HTTP_HOST' => 'speedweek.bergmolen.nl',
            'HTTP_X_FORWARDED_PROTO' => 'https',
            'HTTP_X_FORWARDED_HOST' => 'speedweek.bergmolen.nl',
            'HTTP_X_FORWARDED_PORT' => '443',
            'REMOTE_ADDR' => '10.0.0.1',
            'HTTPS' => 'off',
        ])->get('/login')
            ->assertOk()
            ->assertSee('action="https://speedweek.bergmolen.nl/login"', false)
            ->assertDontSee('http://speedweek.bergmolen.nl', false);
    }

    public function test_new_user_registration_with_event_context_redirects_to_event_registration(): void
    {
        [$event] = $this->eventWithPackages();

        $this->get('/register?event='.$event->slug)
            ->assertOk()
            ->assertSee('name="event_slug"', false)
            ->assertSee('value="'.$event->slug.'"', false);

        $this->post('/register', [
            'name' => 'New Rider',
            'email' => 'new-rider@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'event_slug' => $event->slug,
        ])->assertRedirect(route('events.register', $event));
    }

    public function test_spectator_does_not_have_to_enter_motorcycle(): void
    {
        [$event, , $spectator] = $this->eventWithPackages();
        $user = User::create(['name'=>'Spec','email'=>'spec@example.com','password'=>'password']);
        Registration::create(['user_id'=>$user->id,'event_id'=>$event->id,'package_id'=>$spectator->id,'status'=>'pending','payment_status'=>'deposit_invoiced','total_amount_cents'=>0,'deposit_amount_cents'=>0]);

        $this->actingAs($user)->get('/dashboard/motorcycle')->assertOk()->assertSee('geen motor nodig');
    }

    public function test_rider_can_store_motorcycle(): void
    {
        [$event, $package] = $this->eventWithPackages();
        $user = User::create(['name'=>'Rider','email'=>'rider@example.com','password'=>'password']);
        Registration::create(['user_id'=>$user->id,'event_id'=>$event->id,'package_id'=>$package->id,'status'=>'pending','payment_status'=>'deposit_invoiced','total_amount_cents'=>0,'deposit_amount_cents'=>0]);

        $this->actingAs($user)->patch('/dashboard/motorcycle', ['brand'=>'Yamaha','model'=>'R1','year'=>2022,'return_to'=>route('dashboard')])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('motorcycles', ['user_id'=>$user->id,'brand'=>'Yamaha','model'=>'R1']);
    }

    public function test_own_extra_sets_count_may_not_exceed_two(): void
    {
        [$event, $package] = $this->eventWithPackages();
        $user = User::create(['name'=>'Rider','email'=>'tires@example.com','password'=>'password']);
        $registration = Registration::create(['user_id'=>$user->id,'event_id'=>$event->id,'package_id'=>$package->id,'status'=>'pending','payment_status'=>'deposit_invoiced','total_amount_cents'=>0,'deposit_amount_cents'=>0]);
        $registration->motorcycle()->create(['user_id'=>$user->id,'brand'=>'Ducati','model'=>'V4']);

        $this->actingAs($user)->from('/dashboard/tires')->patch('/dashboard/tires', ['own_extra_sets_count'=>3])->assertSessionHasErrors('own_extra_sets_count');
    }

    public function test_deposit_is_thirty_percent(): void
    {
        [$event, $package] = $this->eventWithPackages();
        $user = User::create(['name'=>'Rider','email'=>'deposit@example.com','password'=>'password']);
        $registration = Registration::create(['user_id'=>$user->id,'event_id'=>$event->id,'package_id'=>$package->id,'status'=>'pending','payment_status'=>'deposit_invoiced','total_amount_cents'=>0,'deposit_amount_cents'=>0]);

        $this->assertSame(30000, $registration->fresh()->deposit_amount_cents);
        $this->assertDatabaseHas('invoices', ['registration_id'=>$registration->id,'type'=>'deposit','amount_cents'=>30000]);
    }

    public function test_user_sees_only_own_registration_on_dashboard(): void
    {
        [$event, $ownPackage] = $this->eventWithPackages();
        $otherPackage = Package::create(['event_id'=>$event->id,'name'=>'Other Private Package','slug'=>'other','price_cents'=>70000,'deposit_percentage'=>30,'is_active'=>true]);
        $user = User::create(['name'=>'One','email'=>'one@example.com','password'=>'password']);
        $other = User::create(['name'=>'Two','email'=>'two@example.com','password'=>'password']);
        Registration::create(['user_id'=>$user->id,'event_id'=>$event->id,'package_id'=>$ownPackage->id,'status'=>'pending','payment_status'=>'deposit_invoiced','total_amount_cents'=>0,'deposit_amount_cents'=>0]);
        Registration::create(['user_id'=>$other->id,'event_id'=>$event->id,'package_id'=>$otherPackage->id,'status'=>'pending','payment_status'=>'deposit_invoiced','total_amount_cents'=>0,'deposit_amount_cents'=>0]);

        $this->actingAs($user)->get('/dashboard')->assertOk()->assertSee('Independent')->assertDontSee('Other Private Package');
    }



    public function test_dashboard_shows_finance_widget_and_configured_contact_details(): void
    {
        [$event, $package] = $this->eventWithPackages();
        $user = User::create(['name'=>'Finance User','email'=>'finance@example.com','password'=>'password']);
        Registration::create(['user_id'=>$user->id,'event_id'=>$event->id,'package_id'=>$package->id,'status'=>'pending','payment_status'=>'deposit_invoiced','total_amount_cents'=>0,'deposit_amount_cents'=>0]);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSee('Finance')
            ->assertSee('Factuur')
            ->assertSee(config('speedweek.contact.name'))
            ->assertSee(config('speedweek.contact.email'));
    }

    public function test_admin_can_access_filament_resources(): void
    {
        $admin = User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>'password','is_admin'=>true]);

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Operations dashboard')->assertSee('Registraties')->assertSee('Gebruikers')->assertSee('Finance');
        $this->actingAs($admin)->get('/admin/users')->assertOk();
        $this->actingAs($admin)->get('/admin/registrations')->assertOk();
    }

    public function test_admin_navigation_shows_management_tabs(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin-tabs@example.com', 'password' => 'password', 'is_admin' => true]);

        $this->actingAs($admin)->get('/dashboard')
            ->assertOk()
            ->assertSee('Gebruikers')
            ->assertSee('Finance')
            ->assertSee('Track results')
            ->assertSee(route('filament.admin.resources.users.index'), false)
            ->assertSee(route('filament.admin.resources.invoices.index'), false);
    }

    public function test_admin_can_open_regular_dashboard_without_participant_onboarding(): void
    {
        $admin = User::create(['name' => 'Admin Dashboard', 'email' => 'admin-dashboard@example.com', 'password' => 'password', 'is_admin' => true]);

        $this->actingAs($admin)->get('/dashboard')
            ->assertOk()
            ->assertSee('Admin dashboardweergave')
            ->assertDontSee('Je registratie is nog niet compleet')
            ->assertSee('Admin')
            ->assertSee(route('filament.admin.pages.dashboard'), false);
    }

    public function test_user_onboarding_state_persists_after_logout_and_login(): void
    {
        $user = User::create(['name' => 'Persist Rider', 'email' => 'persist-onboarding@example.com', 'password' => 'password']);

        app(\App\Services\UserOnboardingService::class)->createForUser($user);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSee('Mijn Speedweek')
            ->assertDontSee('Je registratie is nog niet compleet');

        $this->post('/logout')->assertRedirect('/');

        $this->post('/login', [
            'email' => 'persist-onboarding@example.com',
            'password' => 'password',
            'remember' => '1',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Mijn Speedweek')
            ->assertDontSee('Je registratie is nog niet compleet');
    }

    public function test_track_results_page_shows_lap_times(): void
    {
        $user = User::create(['name' => 'Lap Rider', 'email' => 'lap@example.com', 'password' => 'password']);

        $this->actingAs($user)->get('/dashboard/track-results')
            ->assertOk()
            ->assertSee('Track results')
            ->assertSee('Rondetijden')
            ->assertSee('1:48.231')
            ->assertSee('Lap Rider');
    }

    public function test_dashboard_uses_dutch_status_labels(): void
    {
        [$event, $package] = $this->eventWithPackages();
        $user = User::create(['name' => 'Dutch Status', 'email' => 'dutch-status@example.com', 'password' => 'password']);
        Registration::create(['user_id' => $user->id, 'event_id' => $event->id, 'package_id' => $package->id, 'status' => 'pending', 'payment_status' => 'deposit_invoiced', 'total_amount_cents' => 0, 'deposit_amount_cents' => 0]);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSee('in behandeling')
            ->assertSee('aanbetaling verstuurd')
            ->assertDontSee('deposit_invoiced');
    }
}
