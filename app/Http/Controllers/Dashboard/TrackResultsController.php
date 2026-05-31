<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\TrackResultsService;
use Illuminate\Http\Request;

class TrackResultsController extends Controller
{
    public function __invoke(Request $request, TrackResultsService $trackResults)
    {
        $trackdayTitle = 'Trackday 1, donderdag 1 oktober 2026';
        $sessions = $trackResults->sessionsForUser($request->user());
        $personalLapTimes = $trackResults->personalLapTimesForUser($request->user());
        $personalOverview = $trackResults->summarizePersonalLapTimes($personalLapTimes);

        return view('dashboard.track-results', compact('trackdayTitle', 'sessions', 'personalLapTimes', 'personalOverview'));
    }
}