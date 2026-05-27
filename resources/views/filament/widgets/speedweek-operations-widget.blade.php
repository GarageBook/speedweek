<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide" style="color: #d0362e;">Operations dashboard</p>
                <h2 class="text-2xl font-bold text-black">Speedweek beheer</h2>
                <p class="mt-1 text-sm text-gray-600">Snelle toegang tot registraties, finance en planning.</p>
            </div>
            <div class="grid gap-3 md:grid-cols-4">
                <div class="rounded-lg border border-gray-200 p-4 text-black">
                    <p class="text-sm text-gray-500">Bevestigd</p>
                    <p class="text-2xl font-bold">{{ $confirmedCount }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-4 text-black">
                    <p class="text-sm text-gray-500">In behandeling</p>
                    <p class="text-2xl font-bold">{{ $pendingCount }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-4 text-black">
                    <p class="text-sm text-gray-500">Open aanbetalingen</p>
                    <p class="text-2xl font-bold">EUR {{ number_format($depositDue / 100, 2) }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-4 text-black">
                    <p class="text-sm text-gray-500">Transportmotoren</p>
                    <p class="text-2xl font-bold">{{ $transportCount }}</p>
                </div>
            </div>
            <div class="grid gap-3 md:grid-cols-4">
            <a href="{{ route('filament.admin.resources.registrations.index') }}" class="rounded-lg border border-gray-200 p-4 text-black hover:bg-gray-50">
                <p class="font-bold">Registraties</p>
                <p class="mt-1 text-sm text-gray-700">Deelnemers, pakketten en status.</p>
            </a>
            <a href="{{ route('filament.admin.resources.invoices.index') }}" class="rounded-lg border border-gray-200 p-4 text-black hover:bg-gray-50">
                <p class="font-bold">Finance</p>
                <p class="mt-1 text-sm text-gray-700">Facturen, bedragen en betaalstatus.</p>
            </a>
            <a href="{{ route('filament.admin.resources.users.index') }}" class="rounded-lg border border-gray-200 p-4 text-black hover:bg-gray-50">
                <p class="font-bold">Gebruikers</p>
                <p class="mt-1 text-sm text-gray-700">Accounts en adminrechten.</p>
            </a>
            <a href="{{ route('filament.admin.resources.programme-items.index') }}" class="rounded-lg border border-gray-200 p-4 text-black hover:bg-gray-50">
                <p class="font-bold">Programma</p>
                <p class="mt-1 text-sm text-gray-700">Reisdagen, baansessies en briefings.</p>
            </a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
