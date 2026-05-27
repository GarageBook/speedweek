<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class DashboardController extends Controller { public function __invoke(Request $request) { $registration = $request->user()->registrations()->with(['event','package.features','motorcycle','tireRequest','travelInfo','checklistItems.checklistItem','invoices'])->latest()->first(); return view('dashboard.speedweek', compact('registration')); } }
