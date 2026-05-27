<?php

namespace App\Http\Controllers;

use App\Services\UserOnboardingService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, UserOnboardingService $onboarding)
    {
        $registration = $request->user()
            ->registrations()
            ->with(['event', 'package.features', 'motorcycle', 'tireRequest', 'travelInfo', 'checklistItems.checklistItem', 'invoices'])
            ->latest()
            ->first();

        $onboardingEvent = null;

        if (! $registration) {
            $onboardingEvent = $onboarding->defaultEvent();
        }

        return view('dashboard.speedweek', compact('registration', 'onboardingEvent'));
    }
}
