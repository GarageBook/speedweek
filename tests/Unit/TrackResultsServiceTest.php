<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Package;
use App\Models\User;
use App\Services\TrackResultsService;
use App\Services\UserOnboardingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrackResultsServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_builds_rich_personal_demo_sessions_with_progression(): void
    {
        $event = Event::create([
            'name' => 'Speedweek 2026',
            'slug' => 'speedweek-2026-demo',
            'starts_at' => '2026-11-25',
            'ends_at' => '2026-12-02',
            'final_payment_due_at' => '2026-10-01',
            'location_name' => 'Almeria, Spain',
            'circuit_name' => 'Circuito de Almeria',
            'hotel_name' => 'Hotel Punta del Cantal',
            'status' => 'open',
        ]);

        $package = Package::create([
            'event_id' => $event->id,
            'name' => 'Track Package',
            'slug' => 'track-package',
            'price_cents' => 149500,
            'deposit_percentage' => 30,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Lap Hero',
            'email' => 'lap-hero@example.com',
            'password' => 'password',
        ]);

        app(UserOnboardingService::class)->createForUser($user, $event, $package, [
            'motorcycle_brand' => 'Ducati',
            'motorcycle_model' => 'Panigale V4',
            'motorcycle_year' => 2024,
            'has_license_plate' => true,
            'motorcycle_license_plate' => 'MTS-GP',
        ]);

        $service = app(TrackResultsService::class);
        $sessions = $service->personalLapTimesForUser($user);
        $overview = $service->summarizePersonalLapTimes($sessions);

        $this->assertCount(18, $sessions);
        $this->assertSame('Trackdays 1 t/m 3, donderdag 1 oktober t/m zaterdag 3 oktober 2026', $service->trackdayTitle());
        $this->assertStringStartsWith('Trackday 1', $sessions[0]['session_label']);
        $this->assertStringStartsWith('Trackday 3', $sessions[17]['session_label']);
        $this->assertSame(20, count($sessions[0]['laps']));
        $this->assertSame(20, count($sessions[17]['laps']));
        $this->assertTrue($sessions[0]['laps'][0]['lap_ms'] > $sessions[0]['best_lap_ms']);
        $this->assertTrue($sessions[17]['best_lap_ms'] < $sessions[0]['best_lap_ms']);
        $this->assertSame($sessions[17]['best_lap'], $overview['best_lap']);
        $this->assertSame('18 / 360', $overview['session_count'] . ' / ' . $overview['lap_count']);
        $this->assertStringContainsString('sneller', $overview['improvement']);
        $this->assertSame('Paars', $sessions[17]['laps'][17]['trend_label']);
        $this->assertStringStartsWith('1:33.', $overview['best_lap']);
    }
}
