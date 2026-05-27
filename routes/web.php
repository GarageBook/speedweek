<?php

use App\Http\Controllers\Dashboard\ChecklistController;
use App\Http\Controllers\Dashboard\MotorcycleController;
use App\Http\Controllers\Dashboard\ProfileController as PortalProfileController;
use App\Http\Controllers\Dashboard\ProgrammeController;
use App\Http\Controllers\Dashboard\TireController;
use App\Http\Controllers\Dashboard\TravelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\ProfileController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', fn () => view('welcome', ['event' => Schema::hasTable('events') ? Event::where('status', 'open')->first() : null]));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/register-event/{event:slug}', [EventRegistrationController::class, 'create'])->name('events.register');
    Route::post('/register-event/{event:slug}', [EventRegistrationController::class, 'store'])->name('events.register.store');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/dashboard/profile', [PortalProfileController::class, 'edit'])->name('dashboard.profile');
    Route::patch('/dashboard/profile', [PortalProfileController::class, 'update'])->name('dashboard.profile.update');
    Route::get('/dashboard/motorcycle', [MotorcycleController::class, 'edit'])->name('dashboard.motorcycle');
    Route::patch('/dashboard/motorcycle', [MotorcycleController::class, 'update'])->name('dashboard.motorcycle.update');
    Route::get('/dashboard/tires', [TireController::class, 'edit'])->name('dashboard.tires');
    Route::patch('/dashboard/tires', [TireController::class, 'update'])->name('dashboard.tires.update');
    Route::get('/dashboard/checklist', [ChecklistController::class, 'index'])->name('dashboard.checklist');
    Route::patch('/dashboard/checklist', [ChecklistController::class, 'update'])->name('dashboard.checklist.update');
    Route::get('/dashboard/travel', [TravelController::class, 'edit'])->name('dashboard.travel');
    Route::patch('/dashboard/travel', [TravelController::class, 'update'])->name('dashboard.travel.update');
    Route::get('/dashboard/programme', ProgrammeController::class)->name('dashboard.programme');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
