<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Registration;
use Filament\Widgets\Widget;

class SpeedweekOperationsWidget extends Widget
{
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.speedweek-operations-widget';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $event = Event::query()->where('status', 'open')->latest('starts_at')->first()
            ?? Event::query()->latest('starts_at')->first();

        return [
            'eventName' => $event?->name ?? 'Speedweek 2026',
            'eventDates' => $event ? $event->starts_at->format('d M Y').' - '.$event->ends_at->format('d M Y') : '25 Nov 2026 - 02 Dec 2026',
            'circuitName' => $event?->circuit_name ?? 'Circuito de Almería',
            'hotelName' => $event?->hotel_name ?? 'Hotel Punta del Cantal',
            'confirmedCount' => Registration::query()->where('status', 'confirmed')->count(),
            'pendingCount' => Registration::query()->where('status', 'pending')->count(),
            'depositDue' => Registration::query()->whereIn('payment_status', ['unpaid', 'deposit_invoiced'])->sum('deposit_amount_cents'),
            'transportCount' => Registration::query()->whereHas('package.features', fn ($query) => $query->where('key', 'motorcycle_transport_included')->where('included', true))->count(),
        ];
    }
}
