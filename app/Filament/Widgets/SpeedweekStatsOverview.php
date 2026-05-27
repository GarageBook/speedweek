<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use App\Models\TireRequest;
use App\Support\SpeedweekLabels;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SpeedweekStatsOverview extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $total = Registration::count();
        $payment = Registration::selectRaw('payment_status, count(*) as aggregate')
            ->groupBy('payment_status')
            ->pluck('aggregate', 'payment_status')
            ->map(fn ($value) => (int) $value)
            ->all();
        $transport = Registration::whereHas('package.features', fn ($query) => $query->where('key', 'motorcycle_transport_included')->where('included', true))->count();
        $revenue = Registration::sum('total_amount_cents');
        $depositDue = Registration::whereIn('payment_status', ['unpaid', 'deposit_invoiced'])->sum('deposit_amount_cents');
        $depositPaid = Registration::whereIn('payment_status', ['deposit_paid', 'fully_paid'])->sum('deposit_amount_cents');
        $finalDue = Registration::whereNotIn('payment_status', ['fully_paid', 'refunded', 'credited'])->get()->sum(fn ($registration) => max(0, $registration->total_amount_cents - $registration->deposit_amount_cents));

        return [
            Stat::make('Registraties totaal', $total),
            Stat::make('Betaalstatus', collect($payment)->map(fn ($value, $key) => SpeedweekLabels::paymentStatus($key).': '.$value)->join(' | ') ?: 'Geen'),
            Stat::make('Bandenservice aanvragen', TireRequest::where('wants_tire_service', true)->count()),
            Stat::make('Motortransport', $transport),
            Stat::make('Omzet totaal', 'EUR '.number_format($revenue / 100, 2)),
            Stat::make('Aanbetaling open / betaald', 'EUR '.number_format($depositDue / 100, 2).' / EUR '.number_format($depositPaid / 100, 2)),
            Stat::make('Eindbetaling open', 'EUR '.number_format($finalDue / 100, 2)),
        ];
    }
}
