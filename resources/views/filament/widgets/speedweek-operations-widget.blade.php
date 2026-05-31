<x-filament-widgets::widget>
    @php
        $eventDetails = [
            ['label' => 'Evenement', 'value' => $eventName],
            ['label' => 'Datum', 'value' => $eventDates],
            ['label' => 'Circuit', 'value' => $circuitName],
            ['label' => 'Hotel', 'value' => $hotelName],
        ];

        $stats = [
            [
                'label' => 'Bevestigd',
                'value' => (string) $confirmedCount,
                'status' => 'Actief',
                'valueClass' => 'text-gray-900 dark:text-gray-100',
                'badgeClass' => 'bg-emerald-100 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-500/30',
            ],
            [
                'label' => 'In behandeling',
                'value' => (string) $pendingCount,
                'status' => 'Wachtlijst',
                'valueClass' => 'text-gray-900 dark:text-gray-100',
                'badgeClass' => 'bg-amber-100 text-amber-800 ring-amber-200 dark:bg-amber-500/15 dark:text-amber-300 dark:ring-amber-500/30',
            ],
            [
                'label' => 'Open aanbetalingen',
                'value' => 'EUR '.number_format($depositDue / 100, 2),
                'status' => 'Open',
                'valueClass' => 'text-gray-900 dark:text-gray-100',
                'badgeClass' => 'bg-rose-100 text-rose-700 ring-rose-200 dark:bg-rose-500/15 dark:text-rose-300 dark:ring-rose-500/30',
            ],
            [
                'label' => 'Transportmotoren',
                'value' => (string) $transportCount,
                'status' => 'Logistiek',
                'valueClass' => 'text-gray-900 dark:text-gray-100',
                'badgeClass' => 'bg-sky-100 text-sky-700 ring-sky-200 dark:bg-sky-500/15 dark:text-sky-300 dark:ring-sky-500/30',
            ],
        ];

        $actions = [
            [
                'title' => 'Registraties',
                'description' => 'Deelnemers, pakketten en status.',
                'cta' => 'Open registraties beheren',
                'href' => route('filament.admin.resources.registrations.index'),
            ],
            [
                'title' => 'Finance',
                'description' => 'Facturen, bedragen en betaalstatus.',
                'cta' => 'Facturen en betalingen bekijken',
                'href' => route('filament.admin.resources.invoices.index'),
            ],
            [
                'title' => 'Gebruikers',
                'description' => 'Accounts en adminrechten.',
                'cta' => 'Accounts beheren',
                'href' => route('filament.admin.resources.users.index'),
            ],
            [
                'title' => 'Programma',
                'description' => 'Reisdagen, baansessies en briefings.',
                'cta' => 'Planning en agenda openen',
                'href' => route('filament.admin.resources.programme-items.index'),
            ],
        ];
    @endphp

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="border-b border-gray-200 bg-gradient-to-br from-white via-gray-50 to-gray-100 px-5 py-5 dark:border-gray-800 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 sm:px-6">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">Operations dashboard</p>
                <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-gray-100">Speedweek beheer</h2>
                <p class="max-w-2xl text-sm leading-6 text-gray-600 dark:text-gray-300">Snelle toegang tot registraties, finance en planning.</p>
            </div>
        </div>

        <div class="space-y-6 px-5 py-5 sm:px-6">
            <section class="rounded-2xl border border-gray-200 bg-gray-50/80 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800/80 sm:p-5">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-500 dark:text-gray-400">Event summary</p>
                        <h3 class="mt-1 text-lg font-semibold text-gray-950 dark:text-gray-100">Actief event</h3>
                    </div>
                    <span class="inline-flex shrink-0 rounded-full bg-white px-3 py-1 text-xs font-medium text-gray-700 ring-1 ring-gray-200 dark:bg-gray-900 dark:text-gray-200 dark:ring-gray-700">Dashboard overzicht</span>
                </div>

                <dl class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
                    @foreach ($eventDetails as $detail)
                        <div class="grid grid-cols-1 gap-1 border-b border-gray-200 px-4 py-3 last:border-b-0 sm:grid-cols-[9rem_minmax(0,1fr)] sm:items-center sm:gap-4 dark:border-gray-700">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $detail['label'] }}</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-gray-100 sm:text-right">{{ $detail['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </section>

            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($stats as $stat)
                    <article class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</p>
                            <span class="inline-flex shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $stat['badgeClass'] }}">{{ $stat['status'] }}</span>
                        </div>
                        <p class="mt-4 whitespace-nowrap text-3xl font-bold tracking-tight {{ $stat['valueClass'] }}">{{ $stat['value'] }}</p>
                    </article>
                @endforeach
            </section>

            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($actions as $action)
                    <a href="{{ $action['href'] }}" class="group flex h-full flex-col justify-between rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-base font-semibold text-gray-950 dark:text-gray-100">{{ $action['title'] }}</h3>
                                <span class="mt-0.5 text-gray-400 transition group-hover:text-gray-700 dark:group-hover:text-gray-200">→</span>
                            </div>
                            <p class="text-sm leading-6 text-gray-600 dark:text-gray-300">{{ $action['description'] }}</p>
                        </div>
                        <p class="mt-6 text-xs font-medium text-gray-500 dark:text-gray-400">{{ $action['cta'] }}</p>
                    </a>
                @endforeach
            </section>
        </div>
    </div>
</x-filament-widgets::widget>
