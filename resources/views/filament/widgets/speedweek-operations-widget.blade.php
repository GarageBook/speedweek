<x-filament-widgets::widget>
    <x-filament::section>
        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-200 bg-gradient-to-br from-white via-gray-50 to-gray-100 px-5 py-5 dark:border-gray-800 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 sm:px-6">
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1fr_auto] xl:items-start">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">Operations dashboard</p>
                        <div class="space-y-1">
                            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">Speedweek beheer</h2>
                            <p class="max-w-2xl text-sm leading-6 text-gray-600 dark:text-gray-300">Snelle toegang tot registraties, finance en planning.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white px-4 py-3 text-xs shadow-sm dark:border-gray-700 dark:bg-gray-800 xl:min-w-[22rem]">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Event</p>
                        <p class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">{{ $eventName }}</p>
                        <div class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <div class="flex items-start justify-between gap-4">
                                <span class="font-medium text-gray-500 dark:text-gray-400">Datum</span>
                                <span class="text-right">{{ $eventDates }}</span>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <span class="font-medium text-gray-500 dark:text-gray-400">Circuit</span>
                                <span class="text-right">{{ $circuitName }}</span>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <span class="font-medium text-gray-500 dark:text-gray-400">Hotel</span>
                                <span class="text-right">{{ $hotelName }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-5 py-5 sm:px-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Bevestigd</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100">{{ $confirmedCount }}</p>
                            </div>
                            <div class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Actief</div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">In behandeling</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100">{{ $pendingCount }}</p>
                            </div>
                            <div class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">Wachtlijst</div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Open aanbetalingen</p>
                                <p class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">EUR {{ number_format($depositDue / 100, 2) }}</p>
                            </div>
                            <div class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Open</div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Transportmotoren</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100">{{ $transportCount }}</p>
                            </div>
                            <div class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Logistiek</div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <a href="{{ route('filament.admin.resources.registrations.index') }}" class="group flex h-full flex-col justify-between rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="rounded-2xl bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 dark:bg-gray-700 dark:text-gray-100">Registraties</div>
                                <span class="text-gray-400 transition group-hover:text-gray-700 dark:group-hover:text-gray-200">→</span>
                            </div>
                            <p class="text-sm leading-6 text-gray-600 dark:text-gray-300">Deelnemers, pakketten en status.</p>
                        </div>
                        <p class="mt-5 text-xs font-medium text-gray-500 dark:text-gray-400">Open registraties beheren</p>
                    </a>

                    <a href="{{ route('filament.admin.resources.invoices.index') }}" class="group flex h-full flex-col justify-between rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="rounded-2xl bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 dark:bg-gray-700 dark:text-gray-100">Finance</div>
                                <span class="text-gray-400 transition group-hover:text-gray-700 dark:group-hover:text-gray-200">→</span>
                            </div>
                            <p class="text-sm leading-6 text-gray-600 dark:text-gray-300">Facturen, bedragen en betaalstatus.</p>
                        </div>
                        <p class="mt-5 text-xs font-medium text-gray-500 dark:text-gray-400">Facturen en betalingen bekijken</p>
                    </a>

                    <a href="{{ route('filament.admin.resources.users.index') }}" class="group flex h-full flex-col justify-between rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="rounded-2xl bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 dark:bg-gray-700 dark:text-gray-100">Gebruikers</div>
                                <span class="text-gray-400 transition group-hover:text-gray-700 dark:group-hover:text-gray-200">→</span>
                            </div>
                            <p class="text-sm leading-6 text-gray-600 dark:text-gray-300">Accounts en adminrechten.</p>
                        </div>
                        <p class="mt-5 text-xs font-medium text-gray-500 dark:text-gray-400">Accounts beheren</p>
                    </a>

                    <a href="{{ route('filament.admin.resources.programme-items.index') }}" class="group flex h-full flex-col justify-between rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="rounded-2xl bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 dark:bg-gray-700 dark:text-gray-100">Programma</div>
                                <span class="text-gray-400 transition group-hover:text-gray-700 dark:group-hover:text-gray-200">→</span>
                            </div>
                            <p class="text-sm leading-6 text-gray-600 dark:text-gray-300">Reisdagen, baansessies en briefings.</p>
                        </div>
                        <p class="mt-5 text-xs font-medium text-gray-500 dark:text-gray-400">Planning en agenda openen</p>
                    </a>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
