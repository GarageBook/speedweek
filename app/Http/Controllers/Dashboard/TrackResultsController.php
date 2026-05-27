<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrackResultsController extends Controller
{
    public function __invoke(Request $request)
    {
        $sessions = [
            [
                'name' => 'Track day 1 - ochtend',
                'date' => '26 november 2026',
                'weather' => 'Droog, 18 graden',
                'laps' => [
                    ['position' => 1, 'rider' => $request->user()->name, 'bike' => 'Yamaha R1', 'lap' => '1:48.231', 'gap' => '-'],
                    ['position' => 2, 'rider' => 'Demo Rider', 'bike' => 'Ducati V4', 'lap' => '1:49.004', 'gap' => '+0.773'],
                    ['position' => 3, 'rider' => 'Speedweek Coach', 'bike' => 'BMW S1000RR', 'lap' => '1:50.512', 'gap' => '+2.281'],
                ],
            ],
            [
                'name' => 'Track day 1 - middag',
                'date' => '26 november 2026',
                'weather' => 'Droog, 21 graden',
                'laps' => [
                    ['position' => 1, 'rider' => 'Speedweek Coach', 'bike' => 'BMW S1000RR', 'lap' => '1:47.908', 'gap' => '-'],
                    ['position' => 2, 'rider' => $request->user()->name, 'bike' => 'Yamaha R1', 'lap' => '1:48.017', 'gap' => '+0.109'],
                    ['position' => 3, 'rider' => 'Demo Rider', 'bike' => 'Ducati V4', 'lap' => '1:49.331', 'gap' => '+1.423'],
                ],
            ],
        ];

        return view('dashboard.track-results', compact('sessions'));
    }
}
