<?php

namespace App\Support;

use App\Models\Registration;

class PaymentDisplay
{
    public static function euroFromCents(int $cents): string
    {
        return '€ '.number_format($cents / 100, 2, ',', '.');
    }

    public static function registrationPaymentSummary(?Registration $registration): string
    {
        if (! $registration) {
            return 'Aanbetaling: onbekend • Restfactuur: onbekend';
        }

        $invoices = $registration->invoices;
        $depositPaid = $invoices->where('type', 'deposit')->contains(fn ($invoice) => in_array($invoice->status, ['paid', 'credited'], true));
        $finalPaid = $invoices->where('type', 'final')->contains(fn ($invoice) => in_array($invoice->status, ['paid', 'credited'], true));

        return sprintf(
            'Aanbetaling: %s • Restfactuur: %s',
            $depositPaid ? 'voldaan' : 'niet voldaan',
            $finalPaid ? 'voldaan' : 'niet voldaan'
        );
    }

    public static function registrationPaymentSummaryHtml(?Registration $registration): string
    {
        if (! $registration) {
            return '<span class="text-zinc-400">Geen registratie</span>';
        }

        $invoices = $registration->invoices;
        $depositPaid = $invoices->where('type', 'deposit')->contains(fn ($invoice) => in_array($invoice->status, ['paid', 'credited'], true));
        $finalPaid = $invoices->where('type', 'final')->contains(fn ($invoice) => in_array($invoice->status, ['paid', 'credited'], true));

        $badge = function (bool $paid, string $label): string {
            $classes = $paid
                ? 'bg-emerald-100 text-emerald-800 border-emerald-200'
                : 'bg-amber-100 text-amber-800 border-amber-200';

            return '<span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium '.$classes.'">'.$label.': '.($paid ? 'voldaan' : 'niet voldaan').'</span>';
        };

        return '<div class="flex flex-col gap-1">'.$badge($depositPaid, 'Aanbetaling').$badge($finalPaid, 'Restfactuur').'</div>';
    }
}
