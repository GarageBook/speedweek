<?php

namespace App\Models;

use App\Support\RegistrationPricing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['single_room_requested'=>'boolean','checked_luggage_requested'=>'boolean','confirmed_at'=>'datetime']; }
    protected static function booted(): void { static::saving(function (Registration $registration) { if ($registration->package) { $amounts = RegistrationPricing::amounts($registration->package, (bool) $registration->single_room_requested); $registration->total_amount_cents = $amounts['total']; $registration->deposit_amount_cents = $amounts['deposit']; } }); static::created(fn (Registration $registration) => RegistrationPricing::createInvoices($registration)); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function event(): BelongsTo { return $this->belongsTo(Event::class); }
    public function package(): BelongsTo { return $this->belongsTo(Package::class); }
    public function motorcycle(): HasOne { return $this->hasOne(Motorcycle::class); }
    public function motorcycles(): HasMany { return $this->hasMany(Motorcycle::class); }
    public function tireRequest(): HasOne { return $this->hasOne(TireRequest::class); }
    public function travelInfo(): HasOne { return $this->hasOne(TravelInfo::class); }
    public function checklistItems(): HasMany { return $this->hasMany(ParticipantChecklistItem::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
    public function requiresMotorcycle(): bool { return ! $this->package?->isSpectator(); }
}
