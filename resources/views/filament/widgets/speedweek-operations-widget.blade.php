<x-filament-widgets::widget>
    <x-filament::section>
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-500">Operations dashboard</p>
                <h2 class="text-2xl font-semibold text-gray-900">Speedweek beheer</h2>
                <p class="text-sm text-gray-600">Snelle toegang tot registraties, finance en planning.</p>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-gray-50/70 p-4">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Bevestigd</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $confirmedCount }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-gray-50/70 p-4">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">In behandeling</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $pendingCount }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-gray-50/70 p-4">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Open aanbetalingen</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">EUR {{ number_format($depositDue / 100, 2) }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-gray-50/70 p-4">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Transportmotoren</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $transportCount }}</p>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('filament.admin.resources.registrations.index') }}" class="group rounded-xl border border-gray-200 bg-white p-4 transition hover:border-gray-300 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                    <p class="font-semibold text-gray-900">Registraties</p>
                    <p class="mt-1 text-sm text-gray-600">Deelnemers, pakketten en status.</p>
                </a>

                <a href="{{ route('filament.admin.resources.invoices.index') }}" class="group rounded-xl border border-gray-200 bg-white p-4 transition hover:border-gray-300 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                    <p class="font-semibold text-gray-900">Finance</p>
                    <p class="mt-1 text-sm text-gray-600">Facturen, bedragen en betaalstatus.</p>
                </a>

                <a href="{{ route('filament.admin.resources.users.index') }}" class="group rounded-xl border border-gray-200 bg-white p-4 transition hover:border-gray-300 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                    <p class="font-semibold text-gray-900">Gebruikers</p>
                    <p class="mt-1 text-sm text-gray-600">Accounts en adminrechten.</p>
                </a>

                <a href="{{ route('filament.admin.resources.programme-items.index') }}" class="group rounded-xl border border-gray-200 bg-white p-4 transition hover:border-gray-300 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                    <p class="font-semibold text-gray-900">Programma</p>
                    <p class="mt-1 text-sm text-gray-600">Reisdagen, baansessies en briefings.</p>
                </a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
