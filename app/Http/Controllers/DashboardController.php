<?php

namespace App\Http\Controllers;

use App\Services\UserOnboardingService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, UserOnboardingService $onboarding)
    {
        $user = $request->user();

        $registration = $user
            ->registrations()
            ->with(['event', 'package.features', 'motorcycle', 'tireRequest', 'travelInfo', 'checklistItems.checklistItem', 'invoices'])
            ->latest()
            ->first();

        $isAdminWithoutRegistration = $user->is_admin && ! $registration;
        $onboardingEvent = null;

        if (! $registration && ! $isAdminWithoutRegistration) {
            $onboardingEvent = $onboarding->defaultEvent();
        }

        return view('dashboard.speedweek', compact('registration', 'onboardingEvent', 'isAdminWithoutRegistration'));
    }
}
