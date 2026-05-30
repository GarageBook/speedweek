<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrackResultsController extends Controller
{
    public function __invoke(Request $request)
    {
        $trackdayTitle = 'Trackday 1, donderdag 1 oktober 2026';

        $sessions = [
            [
                'session_number' => 1,
                'start_time' => '10:00',
                'end_time' => '11:00',
                'session_label' => 'Sessie 1: 10:00 - 11:00',
                'weather' => 'Droog, 18 graden',
                'laps' => [
                    ['position' => 1, 'rider' => $request->user()->name, 'bike' => 'Yamaha R1', 'lap' => '1:48.231', 'gap' => '-'],
                    ['position' => 2, 'rider' => 'Demo Rider', 'bike' => 'Ducati V4', 'lap' => '1:49.004', 'gap' => '+0.773'],
                    ['position' => 3, 'rider' => 'Speedweek Coach', 'bike' => 'BMW S1000RR', 'lap' => '1:50.512', 'gap' => '+2.281'],
                ],
            ],
            [
                'session_number' => 2,
                'start_time' => '11:30',
                'end_time' => '12:30',
                'session_label' => 'Sessie 2: 11:30 - 12:30',
                'weather' => 'Droog, 21 graden',
                'laps' => [
                    ['position' => 1, 'rider' => 'Speedweek Coach', 'bike' => 'BMW S1000RR', 'lap' => '1:47.908', 'gap' => '-'],
                    ['position' => 2, 'rider' => $request->user()->name, 'bike' => 'Yamaha R1', 'lap' => '1:48.017', 'gap' => '+0.109'],
                    ['position' => 3, 'rider' => 'Demo Rider', 'bike' => 'Ducati V4', 'lap' => '1:49.331', 'gap' => '+1.423'],
                ],
            ],
            [
                'session_number' => 3,
                'start_time' => '14:00',
                'end_time' => '15:00',
                'session_label' => 'Sessie 3: 14:00 - 15:00',
                'weather' => 'Licht bewolkt, 22 graden',
                'laps' => [
                    ['position' => 1, 'rider' => 'Demo Rider', 'bike' => 'Ducati V4', 'lap' => '1:47.551', 'gap' => '-'],
                    ['position' => 2, 'rider' => 'Speedweek Coach', 'bike' => 'BMW S1000RR', 'lap' => '1:47.992', 'gap' => '+0.441'],
                    ['position' => 3, 'rider' => $request->user()->name, 'bike' => 'Yamaha R1', 'lap' => '1:48.221', 'gap' => '+0.670'],
                ],
            ],
        ];

        $personalLapTimes = array_map(function (array $session) use ($request): array {
            // TODO: replace this dummy filter with persisted lap times linked to the authenticated user.
            $userLaps = array_values(array_filter(
                $session['laps'],
                fn (array $lap): bool => $lap['rider'] === $request->user()->name
            ));

            return [
                'session_number' => $session['session_number'],
                'start_time' => $session['start_time'],
                'end_time' => $session['end_time'],
                'session_label' => $session['session_label'],
                'weather' => $session['weather'],
                'laps' => $userLaps,
            ];
        }, $sessions);

        return view('dashboard.track-results', compact('trackdayTitle', 'sessions', 'personalLapTimes'));
    }
}
