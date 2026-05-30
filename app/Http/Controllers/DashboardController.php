<?php

namespace App\Http\Controllers;

use App\Services\UserOnboardingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        Log::info('dashboard.load.state', [
            'user_id' => $user->id,
            'is_admin' => (bool) $user->is_admin,
            'has_registration' => (bool) $registration,
            'is_admin_without_registration' => $isAdminWithoutRegistration,
            'onboarding_required' => ! $registration && ! $isAdminWithoutRegistration,
            'has_onboarding_event' => (bool) $onboardingEvent,
        ]);

        return view('dashboard.speedweek', compact('registration', 'onboardingEvent', 'isAdminWithoutRegistration'));
    }
}
