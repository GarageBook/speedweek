<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Package;
use App\Services\UserOnboardingService;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function create(Event $event)
    {
        return view('registrations.create', ['event' => $event->load('packages.features')]);
    }

    public function store(Request $request, Event $event, UserOnboardingService $onboarding)
    {
        $data = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'single_room_requested' => ['nullable', 'boolean'],
            'roommate_preference' => ['nullable', 'string', 'max:255'],
            'checked_luggage_requested' => ['nullable', 'boolean'],
            'motorcycle_brand' => ['required', 'string', 'max:255'],
            'motorcycle_model' => ['nullable', 'string', 'max:255'],
            'motorcycle_year' => ['nullable', 'integer', 'between:1900,2100'],
            'has_license_plate' => ['nullable', 'boolean'],
            'motorcycle_license_plate' => ['nullable', 'string', 'max:255'],
            'motorcycle_notes' => ['nullable', 'string'],
        ]);

        $package = Package::where('event_id', $event->id)->findOrFail($data['package_id']);

        $hasLicensePlate = $request->boolean('has_license_plate');

        $onboarding->createForUser($request->user(), $event, $package, [
            'single_room_requested' => $request->boolean('single_room_requested'),
            'roommate_preference' => $data['roommate_preference'] ?? null,
            'checked_luggage_requested' => $request->boolean('checked_luggage_requested'),
            'motorcycle_brand' => $data['motorcycle_brand'],
            'motorcycle_model' => $data['motorcycle_model'] ?? null,
            'motorcycle_year' => $data['motorcycle_year'] ?? null,
            'has_license_plate' => $hasLicensePlate,
            'motorcycle_license_plate' => $hasLicensePlate ? ($data['motorcycle_license_plate'] ?? null) : null,
            'motorcycle_notes' => $data['motorcycle_notes'] ?? null,
        ]);

        return redirect()->route('dashboard');
    }
}
