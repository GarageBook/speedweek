<?php

namespace App\Support;

use App\Models\Invoice;
use App\Models\Package;
use App\Models\Registration;

class RegistrationPricing
{
    public const SINGLE_ROOM_SURCHARGE_CENTS = 25000;

    public static function amounts(Package $package, bool $singleRoomRequested = false): array
    {
        $total = $package->price_cents + ($singleRoomRequested ? self::SINGLE_ROOM_SURCHARGE_CENTS : 0);

        return [
            'total' => $total,
            'deposit' => (int) round($total * ($package->deposit_percentage / 100)),
        ];
    }

    public static function createInvoices(Registration $registration): void
    {
        if ($registration->invoices()->exists()) {
            return;
        }

        Invoice::create([
            'registration_id' => $registration->id,
            'invoice_number' => 'SW-'.$registration->id.'-DEP',
            'type' => 'deposit',
            'status' => 'sent',
            'amount_cents' => $registration->deposit_amount_cents,
            'due_at' => now()->addDays(14)->toDateString(),
            'sent_at' => now(),
        ]);

        Invoice::create([
            'registration_id' => $registration->id,
            'invoice_number' => 'SW-'.$registration->id.'-FINAL',
            'type' => 'final',
            'status' => 'draft',
            'amount_cents' => max(0, $registration->total_amount_cents - $registration->deposit_amount_cents),
            'due_at' => $registration->event->final_payment_due_at,
        ]);
    }
}
