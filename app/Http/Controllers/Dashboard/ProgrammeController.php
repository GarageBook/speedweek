<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller; use Illuminate\Http\Request;
class ProgrammeController extends Controller { public function __invoke(Request $request) { $registration=$request->user()->registrations()->with('event.programmeItems')->latest()->firstOrFail(); return view('dashboard.programme', compact('registration')); } }
