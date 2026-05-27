<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller; use App\Models\ParticipantChecklistItem; use Illuminate\Http\Request;
class ChecklistController extends Controller { public function index(Request $request) { $registration=$request->user()->registrations()->with('checklistItems.checklistItem')->latest()->firstOrFail(); return view('dashboard.checklist', compact('registration')); } public function update(Request $request) { $ids=$request->input('completed', []); $registration=$request->user()->registrations()->latest()->firstOrFail(); foreach ($registration->checklistItems as $item) $item->update(['completed_at'=>in_array($item->id, $ids) ? now() : null]); return back(); } }
