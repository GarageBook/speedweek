<x-speedweek-layout><x-slot:title>Mijn Speedweek</x-slot:title>
@if(! $registration)
    <section class="rounded bg-zinc-900 p-6 border border-zinc-800">
        <h3 class="text-2xl font-bold text-[#d0362e]">Je registratie is nog niet compleet</h3>
        <p class="mt-2 text-zinc-300">Kies je pakket om je Speedweek dashboard te vullen met finance, checklist en motorinformatie.</p>
        @if($onboardingEvent)
            <a class="pill-link mt-4" href="{{ route('events.register', $onboardingEvent) }}">Registratie afronden</a>
        @else
            <p class="mt-4 text-sm text-zinc-400">Er is nog geen open Speedweek event beschikbaar. Neem contact op met de organisatie.</p>
        @endif
    </section>
@else
@php
    $contact = config('speedweek.contact');
    $returnTo = url()->current();
    $money = fn (int $cents) => 'EUR '.number_format($cents / 100, 2);
    $labels = App\Support\SpeedweekLabels::class;
    $openInvoices = $registration->invoices->whereNotIn('status', ['paid', 'cancelled', 'credited']);
    $paidInvoices = $registration->invoices->where('status', 'paid');
    $openAmount = $openInvoices->sum('amount_cents');
    $paidAmount = $paidInvoices->sum('amount_cents');
@endphp
<div class="grid gap-4 md:grid-cols-3">
    <section class="md:col-span-2 rounded bg-zinc-900 p-6 border border-zinc-800">
        <h3 class="text-2xl font-bold text-[#d0362e]">Mijn Speedweek</h3>
        <p class="mt-2">{{ $registration->event->name }} · {{ $registration->package->name }}</p>
        <p class="mt-2"><span class="rounded bg-zinc-800 px-2 py-1">{{ $labels::registrationStatus($registration->status) }}</span> <span class="rounded bg-[#d0362e] text-white px-2 py-1">{{ $labels::paymentStatus($registration->payment_status) }}</span></p>
    </section>
    <section class="rounded bg-zinc-900 p-6 border border-zinc-800">
        <h3 class="font-bold">Contact</h3>
        <p class="text-sm text-zinc-300 mt-2">{{ $contact['name'] }}<br><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a><br>{{ $contact['phone'] }}</p>
    </section>
</div>

<section class="mt-4 rounded bg-zinc-900 p-5 border border-zinc-800">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h4 class="font-semibold text-[#d0362e]">Finance</h4>
            <p class="mt-1 text-sm text-zinc-300">Bekijk facturen, open bedragen en betaalstatus.</p>
        </div>
        <div class="grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
            <div class="rounded border border-zinc-800 p-3"><div class="text-zinc-400">Totaal</div><div class="font-bold">{{ $money($registration->total_amount_cents) }}</div></div>
            <div class="rounded border border-zinc-800 p-3"><div class="text-zinc-400">Aanbetaling</div><div class="font-bold">{{ $money($registration->deposit_amount_cents) }}</div></div>
            <div class="rounded border border-zinc-800 p-3"><div class="text-zinc-400">Betaald</div><div class="font-bold">{{ $money($paidAmount) }}</div></div>
            <div class="rounded border border-zinc-800 p-3"><div class="text-zinc-400">Open</div><div class="font-bold">{{ $money($openAmount) }}</div></div>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="text-left text-zinc-400"><tr><th class="py-2 pr-4">Factuur</th><th class="py-2 pr-4">Type</th><th class="py-2 pr-4">Status</th><th class="py-2 pr-4">Bedrag</th><th class="py-2 pr-4">Vervalt</th><th class="py-2">Actie</th></tr></thead>
            <tbody class="divide-y divide-zinc-800">
                @forelse($registration->invoices as $invoice)
                    <tr>
                        <td class="py-3 pr-4 font-bold">{{ $invoice->invoice_number }}</td>
                        <td class="py-3 pr-4">{{ $labels::invoiceType($invoice->type) }}</td>
                        <td class="py-3 pr-4"><span class="rounded bg-zinc-800 px-2 py-1">{{ $labels::invoiceStatus($invoice->status) }}</span></td>
                        <td class="py-3 pr-4">{{ $money($invoice->amount_cents) }}</td>
                        <td class="py-3 pr-4">{{ $invoice->due_at?->format('d M Y') ?? 'TBD' }}</td>
                        <td class="py-3">
                            @if(! in_array($invoice->status, ['paid', 'cancelled', 'credited'], true))
                                <a class="pill-link" href="mailto:{{ $contact['email'] }}?subject=Betaling%20{{ rawurlencode($invoice->invoice_number) }}">Direct betalen</a>
                            @else
                                <span class="text-zinc-400">Geen actie</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-3 text-zinc-400">Er zijn nog geen facturen aangemaakt.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="mt-3 text-xs text-zinc-500">Betaalprovider is nog niet gekoppeld; de knop opent voorlopig een betaalverzoek per mail.</p>
</section>

<div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
<section class="rounded bg-zinc-900 p-5 border border-zinc-800"><h4 class="font-semibold text-[#d0362e]">Package features</h4><ul class="mt-2 text-sm space-y-1">@foreach($registration->package->features as $feature)<li>{{ $feature->included ? '✓' : '–' }} {{ $feature->label }}</li>@endforeach</ul></section>
<section class="rounded bg-zinc-900 p-5 border border-zinc-800"><h4 class="font-semibold text-[#d0362e]">Open checklist</h4><p>{{ $registration->checklistItems->whereNull('completed_at')->count() }} open items</p><a class="pill-link mt-3" href="{{ route('dashboard.checklist', ['return_to' => $returnTo]) }}">Open checklist</a></section>
<section class="rounded bg-zinc-900 p-5 border border-zinc-800"><h4 class="font-semibold text-[#d0362e]">Mijn motor</h4><p>{{ $registration->motorcycle?->brand ?? 'Niet ingevuld' }} {{ $registration->motorcycle?->model }}</p><a class="pill-link mt-3" href="{{ route('dashboard.motorcycle', ['return_to' => $returnTo]) }}">Bewerken</a></section>
<section class="rounded bg-zinc-900 p-5 border border-zinc-800"><h4 class="font-semibold text-[#d0362e]">Bandenkeuze</h4><p>{{ $registration->tireRequest?->preferred_brand ?? 'Nog niet gekozen' }}</p><a class="pill-link mt-3" href="{{ route('dashboard.tires', ['return_to' => $returnTo]) }}">Bewerken</a></section>
<section class="rounded bg-zinc-900 p-5 border border-zinc-800"><h4 class="font-semibold text-[#d0362e]">Reisinfo / hotelinfo</h4><p>{{ $registration->travelInfo?->outbound_flight_number ?? 'Nog niet ingevuld' }} · kamer {{ $registration->travelInfo?->hotel_room_number ?? 'TBD' }}</p><a class="pill-link mt-3" href="{{ route('dashboard.travel', ['return_to' => $returnTo]) }}">Bewerken</a></section>
<section class="rounded bg-zinc-900 p-5 border border-zinc-800"><h4 class="font-semibold text-[#d0362e]">Belangrijke adressen</h4><p class="text-sm">{{ $registration->event->circuit_name }}<br>{{ $registration->event->circuit_address }}<br><br>{{ $registration->event->hotel_name }}<br>{{ $registration->event->hotel_address }}</p></section>
</div>
<section class="mt-4 rounded bg-zinc-900 p-5 border border-zinc-800"><h4 class="font-semibold text-[#d0362e]">Programma</h4><div class="mt-2 grid gap-2">@foreach($registration->event->programmeItems->sortBy('starts_at')->take(6) as $item)<div class="flex justify-between border-b border-zinc-800 py-2"><span>{{ $item->title }}</span><span class="text-zinc-400">{{ $item->starts_at->format('d M H:i') }}</span></div>@endforeach</div><a class="pill-link mt-4" href="{{ route('dashboard.programme') }}">Volledig programma</a></section>
@endif
</x-speedweek-layout>
