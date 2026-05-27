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
            ->assertSee('/register?event='.$event->slug, false);
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

        $this->actingAs($user)->patch('/dashboard/motorcycle', ['brand'=>'Yamaha','model'=>'R1','year'=>2022])->assertRedirect();

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

    public function test_admin_can_access_filament_resources(): void
    {
        $admin = User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>'password','is_admin'=>true]);

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Operations dashboard')->assertSee('Registrations');
        $this->actingAs($admin)->get('/admin/users')->assertOk();
        $this->actingAs($admin)->get('/admin/registrations')->assertOk();
    }
}
