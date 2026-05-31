<x-filament-widgets::widget>
    @php
        $eventDetails = [
            ['label' => 'Evenement', 'value' => $eventName],
            ['label' => 'Datum', 'value' => $eventDates],
            ['label' => 'Circuit', 'value' => $circuitName],
            ['label' => 'Hotel', 'value' => $hotelName],
        ];

        $stats = [
            ['label' => 'Bevestigd', 'value' => (string) $confirmedCount, 'status' => 'Actief', 'badgeClass' => 'sw-ops-pill sw-ops-pill-emerald'],
            ['label' => 'In behandeling', 'value' => (string) $pendingCount, 'status' => 'Wachtlijst', 'badgeClass' => 'sw-ops-pill sw-ops-pill-amber'],
            ['label' => 'Open aanbetalingen', 'value' => 'EUR '.number_format($depositDue / 100, 2), 'status' => 'Open', 'badgeClass' => 'sw-ops-pill sw-ops-pill-rose'],
            ['label' => 'Transportmotoren', 'value' => (string) $transportCount, 'status' => 'Logistiek', 'badgeClass' => 'sw-ops-pill sw-ops-pill-sky'],
        ];

        $actions = [
            ['title' => 'Registraties', 'description' => 'Deelnemers, pakketten en status.', 'cta' => 'Open registraties beheren', 'href' => route('filament.admin.resources.registrations.index')],
            ['title' => 'Finance', 'description' => 'Facturen, bedragen en betaalstatus.', 'cta' => 'Facturen en betalingen bekijken', 'href' => route('filament.admin.resources.invoices.index')],
            ['title' => 'Gebruikers', 'description' => 'Accounts en adminrechten.', 'cta' => 'Accounts beheren', 'href' => route('filament.admin.resources.users.index')],
            ['title' => 'Programma', 'description' => 'Reisdagen, baansessies en briefings.', 'cta' => 'Planning en agenda openen', 'href' => route('filament.admin.resources.programme-items.index')],
        ];
    @endphp

    <div class="sw-ops-widget">
        <div class="sw-ops-shell">
            <div class="sw-ops-header">
                <p class="sw-ops-eyebrow">Operations dashboard</p>
                <h2 class="sw-ops-title">Speedweek beheer</h2>
                <p class="sw-ops-intro">Snelle toegang tot registraties, finance en planning.</p>
            </div>

            <div class="sw-ops-body">
                <section class="sw-ops-event-card">
                    <div class="sw-ops-event-heading">
                        <div>
                            <p class="sw-ops-event-kicker">Event summary</p>
                            <h3 class="sw-ops-event-title">Actief event</h3>
                        </div>
                        <span class="sw-ops-event-badge">Dashboard overzicht</span>
                    </div>

                    <dl class="sw-ops-event-list">
                        @foreach ($eventDetails as $detail)
                            <div class="sw-ops-event-row">
                                <dt class="sw-ops-event-label">{{ $detail['label'] }}</dt>
                                <dd class="sw-ops-event-value">{{ $detail['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </section>

                <section class="sw-ops-metrics">
                    @foreach ($stats as $stat)
                        <article class="sw-ops-stat">
                            <div class="sw-ops-stat-top">
                                <p class="sw-ops-stat-label">{{ $stat['label'] }}</p>
                                <span class="{{ $stat['badgeClass'] }}">{{ $stat['status'] }}</span>
                            </div>
                            <p class="sw-ops-stat-value">{{ $stat['value'] }}</p>
                        </article>
                    @endforeach
                </section>

                <section class="sw-ops-actions">
                    @foreach ($actions as $action)
                        <a href="{{ $action['href'] }}" class="sw-ops-action">
                            <div class="sw-ops-action-top">
                                <h3 class="sw-ops-action-title">{{ $action['title'] }}</h3>
                                <span class="sw-ops-action-arrow">&rarr;</span>
                            </div>
                            <p class="sw-ops-action-copy">{{ $action['description'] }}</p>
                            <p class="sw-ops-action-cta">{{ $action['cta'] }}</p>
                        </a>
                    @endforeach
                </section>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
