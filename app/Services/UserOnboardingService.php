<?php

namespace App\Services;

use App\Models\ChecklistItem;
use App\Models\Event;
use App\Models\Motorcycle;
use App\Models\Package;
use App\Models\ParticipantChecklistItem;
use App\Models\ProgrammeItem;
use App\Models\Registration;
use App\Models\TireRequest;
use App\Models\TravelInfo;
use App\Models\User;
use App\Support\RegistrationPricing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserOnboardingService
{
    public function createForUser(User $user, ?Event $event = null, ?Package $package = null, array $attributes = []): Registration
    {
        return DB::transaction(function () use ($user, $event, $package, $attributes): Registration {
            if ($registration = $user->registrations()->latest()->first()) {
                Log::info('onboarding.create_for_user.existing_registration', [
                    'user_id' => $user->id,
                    'registration_id' => $registration->id,
                ]);

                $this->ensureDashboardData($registration);

                return $registration->fresh(['event', 'package', 'motorcycle', 'tireRequest', 'travelInfo', 'checklistItems', 'invoices']);
            }

            $event = $event ?? $this->defaultEvent();
            $package = $package ?? $this->defaultPackage($event);

            $registration = Registration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'package_id' => $package->id,
                'status' => 'pending',
                'payment_status' => 'deposit_invoiced',
                'single_room_requested' => $attributes['single_room_requested'] ?? false,
                'roommate_preference' => $attributes['roommate_preference'] ?? null,
                'checked_luggage_requested' => $attributes['checked_luggage_requested'] ?? true,
                'total_amount_cents' => $package->price_cents,
                'deposit_amount_cents' => 0,
            ]);

            Log::info('onboarding.create_for_user.registration_created', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
            ]);

            $this->ensureDashboardData($registration);

            return $registration->fresh(['event', 'package', 'motorcycle', 'tireRequest', 'travelInfo', 'checklistItems', 'invoices']);
        });
    }

    public function ensureDashboardData(Registration $registration): void
    {
        $registration->loadMissing('event', 'package', 'motorcycle', 'invoices');

        if (! $registration->invoices()->exists()) {
            RegistrationPricing::createInvoices($registration);
        }

        $this->ensureChecklistItems($registration);

        if ($registration->requiresMotorcycle()) {
            $motorcycle = $this->ensureMotorcycle($registration);
            $this->ensureTireRequest($registration, $motorcycle);
        }

        $this->ensureTravelInfo($registration);
    }

    public function defaultEvent(): Event
    {
        $event = Event::query()->where('status', 'open')->latest('starts_at')->first()
            ?? Event::query()->latest('starts_at')->first()
            ?? $this->createDefaultEvent();

        $this->ensureDefaultPackages($event);
        $this->ensureDefaultChecklistItems($event);
        $this->ensureDefaultProgramme($event);

        return $event->fresh(['packages.features', 'checklistItems', 'programmeItems']);
    }

    private function defaultPackage(Event $event): Package
    {
        $this->ensureDefaultPackages($event);

        return $event->packages()->where('slug', 'full-package')->where('is_active', true)->first()
            ?? $event->packages()->where('is_active', true)->orderBy('sort_order')->first()
            ?? $event->packages()->orderBy('sort_order')->first();
    }

    private function createDefaultEvent(): Event
    {
        return Event::create([
            'name' => 'Speedweek 2026',
            'slug' => 'speedweek-2026',
            'starts_at' => '2026-11-25',
            'ends_at' => '2026-12-02',
            'final_payment_due_at' => '2026-10-01',
            'location_name' => 'Almeria, Spain',
            'circuit_name' => 'Circuito de Almeria',
            'circuit_address' => 'Carretera AL-3102, Tabernas, Almeria, Spain',
            'hotel_name' => 'Hotel Punta del Cantal',
            'hotel_address' => 'Av. del Mediterraneo, Mojacar, Almeria, Spain',
            'bike_dropoff_location' => 'Speedweek Transport Hub, NL',
            'bike_dropoff_datetime' => '2026-11-18 10:00:00',
            'return_datetime' => '2026-12-07 16:00:00',
            'description' => 'Meerdaags circuit-event in Zuid-Spanje met rijden, begeleiding en praktische ontzorging.',
            'status' => 'open',
        ]);
    }

    private function ensureDefaultPackages(Event $event): void
    {
        $definitions = [
            'full-package' => ['Full Package', 249500, ['circuit_riding_days', 'hotel_included', 'breakfast_included', 'dinner_included', 'flight_included', 'motorcycle_transport_included', 'daily_bus_transfer_included', 'fuel_included', 'pitbox_included', 'tire_service_available', 'instructor_guidance', 'transponder_timing', 'photography', 'certificate']],
            'independent' => ['Independent', 139500, ['circuit_riding_days', 'instructor_guidance', 'transponder_timing', 'photography', 'certificate', 'tire_service_available']],
            'spectator' => ['Spectator', 89500, ['hotel_included', 'breakfast_included', 'dinner_included', 'flight_included', 'daily_bus_transfer_included', 'photography']],
        ];

        $labels = [
            'circuit_riding_days' => 'Circuit riding days',
            'hotel_included' => 'Hotel included',
            'breakfast_included' => 'Breakfast included',
            'dinner_included' => 'Dinner included',
            'flight_included' => 'Flight included',
            'motorcycle_transport_included' => 'Motorcycle transport included',
            'daily_bus_transfer_included' => 'Daily bus transfer included',
            'fuel_included' => 'Fuel included',
            'pitbox_included' => 'Pitbox included',
            'tire_service_available' => 'Tire service available',
            'instructor_guidance' => 'Instructor guidance',
            'transponder_timing' => 'Transponder/timing',
            'photography' => 'Photography',
            'certificate' => 'Certificate',
        ];

        $sort = 1;

        foreach ($definitions as $slug => [$name, $price, $included]) {
            $package = Package::firstOrCreate(
                ['event_id' => $event->id, 'slug' => $slug],
                ['name' => $name, 'description' => $name.' voor Speedweek 2026', 'price_cents' => $price, 'deposit_percentage' => 30, 'sort_order' => $sort, 'is_active' => true]
            );

            foreach ($labels as $key => $label) {
                $package->features()->firstOrCreate(
                    ['key' => $key],
                    ['label' => $label, 'included' => in_array($key, $included, true)]
                );
            }

            $sort++;
        }
    }

    private function ensureDefaultChecklistItems(Event $event): void
    {
        $items = [
            ['travel', 'Check flight times'],
            ['motorcycle', 'Prepare motorcycle for transport'],
            ['gear', 'Pack race suit and helmet'],
            ['documents', 'Check passport validity'],
            ['tires', 'Confirm tire plan'],
            ['payment', 'Pay deposit invoice'],
        ];

        foreach ($items as $index => [$category, $title]) {
            ChecklistItem::firstOrCreate(
                ['event_id' => $event->id, 'title' => $title],
                ['category' => $category, 'sort_order' => $index + 1, 'is_default' => true]
            );
        }
    }

    private function ensureDefaultProgramme(Event $event): void
    {
        if ($event->programmeItems()->exists()) {
            return;
        }

        $items = [
            ['Travel day to Almeria', 'travel', '2026-11-25 08:00', '2026-11-25 20:00', 'Schiphol / Almeria'],
            ['Welcome briefing', 'briefing', '2026-11-25 21:00', '2026-11-25 22:00', 'Hotel Punta del Cantal'],
            ['Track day 1', 'track_session', '2026-11-26 09:00', '2026-11-26 17:00', 'Circuito de Almeria'],
            ['Track day 2', 'track_session', '2026-11-27 09:00', '2026-11-27 17:00', 'Circuito de Almeria'],
            ['Rest day', 'rest_day', '2026-11-28 10:00', '2026-11-28 18:00', 'Mojacar'],
            ['Technical briefing', 'technical', '2026-11-29 08:15', '2026-11-29 08:45', 'Pitlane'],
        ];

        foreach ($items as [$title, $type, $start, $end, $location]) {
            ProgrammeItem::create(['event_id' => $event->id, 'title' => $title, 'type' => $type, 'starts_at' => $start, 'ends_at' => $end, 'location' => $location]);
        }
    }

    private function ensureChecklistItems(Registration $registration): void
    {
        $this->ensureDefaultChecklistItems($registration->event);

        ChecklistItem::query()
            ->where('event_id', $registration->event_id)
            ->get()
            ->each(fn (ChecklistItem $item) => ParticipantChecklistItem::firstOrCreate([
                'registration_id' => $registration->id,
                'checklist_item_id' => $item->id,
            ]));
    }

    private function ensureMotorcycle(Registration $registration): Motorcycle
    {
        return $registration->motorcycle()->firstOrCreate(
            ['registration_id' => $registration->id],
            [
                'user_id' => $registration->user_id,
                'brand' => 'Yamaha',
                'model' => 'R1',
                'year' => 2022,
                'license_plate' => 'MTS-01',
                'front_tire_size' => '120/70 ZR17',
                'rear_tire_size' => '200/55 ZR17',
            ]
        );
    }

    private function ensureTireRequest(Registration $registration, Motorcycle $motorcycle): void
    {
        TireRequest::firstOrCreate(
            ['registration_id' => $registration->id],
            [
                'motorcycle_id' => $motorcycle->id,
                'brings_own_tires' => true,
                'own_extra_sets_count' => 1,
                'wants_tire_service' => true,
                'wants_to_order_tires' => false,
                'preferred_brand' => 'Pirelli',
                'front_tire_size' => '120/70 ZR17',
                'rear_tire_size' => '200/55 ZR17',
            ]
        );
    }

    private function ensureTravelInfo(Registration $registration): void
    {
        TravelInfo::firstOrCreate(
            ['registration_id' => $registration->id],
            [
                'outbound_flight_number' => 'HV0001',
                'outbound_departure_airport' => 'AMS',
                'outbound_departure_at' => '2026-11-25 08:30:00',
                'outbound_arrival_airport' => 'LEI',
                'outbound_arrival_at' => '2026-11-25 11:30:00',
                'return_flight_number' => 'HV0002',
                'return_departure_airport' => 'LEI',
                'return_departure_at' => '2026-12-02 15:00:00',
                'return_arrival_airport' => 'AMS',
                'return_arrival_at' => '2026-12-02 18:00:00',
                'hotel_room_number' => 'TBD',
                'bus_group' => 'A',
            ]
        );
    }
}
