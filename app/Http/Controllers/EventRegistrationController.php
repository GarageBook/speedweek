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
        ]);

        $package = Package::where('event_id', $event->id)->findOrFail($data['package_id']);

        $onboarding->createForUser($request->user(), $event, $package, [
            'single_room_requested' => $request->boolean('single_room_requested'),
            'roommate_preference' => $data['roommate_preference'] ?? null,
            'checked_luggage_requested' => $request->boolean('checked_luggage_requested'),
        ]);

        return redirect()->route('dashboard');
    }
}
