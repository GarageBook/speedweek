<x-filament-widgets::widget>
    @php
        $eventDetails = [
            ['label' => 'Evenement', 'value' => $eventName],
            ['label' => 'Datum', 'value' => $eventDates],
            ['label' => 'Circuit', 'value' => $circuitName],
            ['label' => 'Hotel', 'value' => $hotelName],
        ];

        $stats = [
            ['label' => 'Bevestigd', 'value' => (string) $confirmedCount, 'status' => 'Actief'],
            ['label' => 'In behandeling', 'value' => (string) $pendingCount, 'status' => 'Wachtlijst'],
            ['label' => 'Open aanbetalingen', 'value' => 'EUR '.number_format($depositDue / 100, 2), 'status' => 'Open'],
            ['label' => 'Transportmotoren', 'value' => (string) $transportCount, 'status' => 'Logistiek'],
        ];

        $actions = [
            ['title' => 'Registraties', 'description' => 'Deelnemers, pakketten en status.', 'cta' => 'Open registraties beheren', 'href' => route('filament.admin.resources.registrations.index')],
            ['title' => 'Finance', 'description' => 'Facturen, bedragen en betaalstatus.', 'cta' => 'Facturen en betalingen bekijken', 'href' => route('filament.admin.resources.invoices.index')],
            ['title' => 'Gebruikers', 'description' => 'Accounts en adminrechten.', 'cta' => 'Accounts beheren', 'href' => route('filament.admin.resources.users.index')],
            ['title' => 'Programma', 'description' => 'Reisdagen, baansessies en briefings.', 'cta' => 'Planning en agenda openen', 'href' => route('filament.admin.resources.programme-items.index')],
        ];
    @endphp

    <style>
        .swops-widget {
            display: grid;
            gap: 1rem;
            color: #111827;
        }

        .swops-card {
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            background: #ffffff;
            padding: 1.25rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }

        .swops-header {
            display: grid;
            gap: 0.4rem;
        }

        .swops-eyebrow {
            margin: 0;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #6b7280;
        }

        .swops-title {
            margin: 0;
            font-size: 1.5rem;
            line-height: 1.2;
            font-weight: 700;
            color: #111827;
        }

        .swops-intro {
            margin: 0;
            max-width: 40rem;
            font-size: 0.95rem;
            line-height: 1.5;
            color: #4b5563;
        }

        .swops-section-title {
            margin: 0 0 0.85rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #111827;
        }

        .swops-event-list {
            margin: 0;
            display: grid;
            gap: 0;
        }

        .swops-event-row {
            display: grid;
            grid-template-columns: minmax(7rem, 10rem) minmax(0, 1fr);
            gap: 0.85rem;
            padding: 0.8rem 0;
            border-top: 1px solid #f1f5f9;
        }

        .swops-event-row:first-child {
            padding-top: 0;
            border-top: 0;
        }

        .swops-event-row:last-child {
            padding-bottom: 0;
        }

        .swops-event-label {
            margin: 0;
            font-size: 0.85rem;
            font-weight: 600;
            color: #6b7280;
        }

        .swops-event-value {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: #111827;
            word-break: normal;
            overflow-wrap: anywhere;
        }

        .swops-grid {
            display: grid;
            gap: 1rem;
        }

        .swops-kpis,
        .swops-actions {
            grid-template-columns: 1fr;
        }

        .swops-kpi {
            display: grid;
            gap: 0.75rem;
        }

        .swops-kpi-head,
        .swops-action-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .swops-kpi-label,
        .swops-action-title {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: #111827;
        }

        .swops-kpi-value {
            margin: 0;
            font-size: 1.75rem;
            line-height: 1.1;
            font-weight: 800;
            color: #111827;
        }

        .swops-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.3rem 0.65rem;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .swops-pill-emerald {
            background: #dcfce7;
            color: #166534;
        }

        .swops-pill-amber {
            background: #fef3c7;
            color: #92400e;
        }

        .swops-pill-rose {
            background: #ffe4e6;
            color: #9f1239;
        }

        .swops-pill-sky {
            background: #e0f2fe;
            color: #075985;
        }

        .swops-action {
            display: grid;
            gap: 0.85rem;
            color: inherit;
            text-decoration: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
        }

        .swops-action:hover,
        .swops-action:focus-visible {
            border-color: #cbd5e1;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            transform: translateY(-1px);
        }

        .swops-action:focus-visible {
            outline: 2px solid #0ea5e9;
            outline-offset: 2px;
        }

        .swops-action-copy {
            margin: 0;
            font-size: 0.92rem;
            line-height: 1.5;
            color: #4b5563;
        }

        .swops-action-cta {
            margin: 0;
            font-size: 0.88rem;
            font-weight: 700;
            color: #0f766e;
        }

        .swops-arrow {
            flex: 0 0 auto;
            font-size: 1rem;
            font-weight: 700;
            color: #6b7280;
        }

        @media (min-width: 768px) {
            .swops-kpis,
            .swops-actions {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .swops-kpis,
            .swops-actions {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .dark .swops-widget,
        [data-theme="dark"] .swops-widget,
        .fi-theme-dark .swops-widget {
            color: #f8fafc;
        }

        .dark .swops-card,
        [data-theme="dark"] .swops-card,
        .fi-theme-dark .swops-card {
            border-color: #334155;
            background: #0f172a;
            box-shadow: none;
        }

        .dark .swops-title,
        .dark .swops-section-title,
        .dark .swops-event-value,
        .dark .swops-kpi-label,
        .dark .swops-kpi-value,
        .dark .swops-action-title,
        [data-theme="dark"] .swops-title,
        [data-theme="dark"] .swops-section-title,
        [data-theme="dark"] .swops-event-value,
        [data-theme="dark"] .swops-kpi-label,
        [data-theme="dark"] .swops-kpi-value,
        [data-theme="dark"] .swops-action-title,
        .fi-theme-dark .swops-title,
        .fi-theme-dark .swops-section-title,
        .fi-theme-dark .swops-event-value,
        .fi-theme-dark .swops-kpi-label,
        .fi-theme-dark .swops-kpi-value,
        .fi-theme-dark .swops-action-title {
            color: #f8fafc;
        }

        .dark .swops-intro,
        .dark .swops-event-label,
        .dark .swops-action-copy,
        .dark .swops-arrow,
        .dark .swops-eyebrow,
        [data-theme="dark"] .swops-intro,
        [data-theme="dark"] .swops-event-label,
        [data-theme="dark"] .swops-action-copy,
        [data-theme="dark"] .swops-arrow,
        [data-theme="dark"] .swops-eyebrow,
        .fi-theme-dark .swops-intro,
        .fi-theme-dark .swops-event-label,
        .fi-theme-dark .swops-action-copy,
        .fi-theme-dark .swops-arrow,
        .fi-theme-dark .swops-eyebrow {
            color: #cbd5e1;
        }

        .dark .swops-event-row,
        [data-theme="dark"] .swops-event-row,
        .fi-theme-dark .swops-event-row {
            border-top-color: #1e293b;
        }

        .dark .swops-action:hover,
        .dark .swops-action:focus-visible,
        [data-theme="dark"] .swops-action:hover,
        [data-theme="dark"] .swops-action:focus-visible,
        .fi-theme-dark .swops-action:hover,
        .fi-theme-dark .swops-action:focus-visible {
            border-color: #475569;
            box-shadow: 0 10px 28px rgba(2, 6, 23, 0.35);
        }
    </style>

    <div class="swops-widget">
        <section class="swops-card swops-header">
            <p class="swops-eyebrow">Operations dashboard</p>
            <h2 class="swops-title">Speedweek beheer</h2>
            <p class="swops-intro">Snelle toegang tot registraties, finance en planning.</p>
        </section>

        <section class="swops-card">
            <h3 class="swops-section-title">Evenementsoverzicht</h3>

            <dl class="swops-event-list">
                @foreach ($eventDetails as $detail)
                    <div class="swops-event-row">
                        <dt class="swops-event-label">{{ $detail['label'] }}</dt>
                        <dd class="swops-event-value">{{ $detail['value'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <section class="swops-grid swops-kpis" aria-label="KPI overzicht">
            @foreach ($stats as $stat)
                @php
                    $badgeClass = match ($stat['status']) {
                        'Actief' => 'swops-pill swops-pill-emerald',
                        'Wachtlijst' => 'swops-pill swops-pill-amber',
                        'Open' => 'swops-pill swops-pill-rose',
                        default => 'swops-pill swops-pill-sky',
                    };
                @endphp

                <article class="swops-card swops-kpi">
                    <div class="swops-kpi-head">
                        <p class="swops-kpi-label">{{ $stat['label'] }}</p>
                        <span class="{{ $badgeClass }}">{{ $stat['status'] }}</span>
                    </div>
                    <p class="swops-kpi-value">{{ $stat['value'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="swops-grid swops-actions" aria-label="Snelle acties">
            @foreach ($actions as $action)
                <a href="{{ $action['href'] }}" class="swops-card swops-action">
                    <div class="swops-action-head">
                        <h3 class="swops-action-title">{{ $action['title'] }}</h3>
                        <span class="swops-arrow" aria-hidden="true">&rarr;</span>
                    </div>
                    <p class="swops-action-copy">{{ $action['description'] }}</p>
                    <p class="swops-action-cta">{{ $action['cta'] }}</p>
                </a>
            @endforeach
        </section>
    </div>
</x-filament-widgets::widget>
