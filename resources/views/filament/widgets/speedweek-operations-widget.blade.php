<x-filament-widgets::widget>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide" style="color: #d0362e;">Operations dashboard</p>
                <h2 class="mt-1 text-2xl font-bold text-black">{{ $eventName }}</h2>
                <p class="mt-1 text-sm text-gray-700">{{ $eventDates }} · {{ $circuitName }}</p>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-lg border border-gray-200 p-3">
                    <div class="text-gray-600">Confirmed</div>
                    <div class="text-xl font-bold text-black">{{ $confirmedCount }}</div>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <div class="text-gray-600">Pending</div>
                    <div class="text-xl font-bold text-black">{{ $pendingCount }}</div>
                </div>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('filament.admin.resources.registrations.index') }}" class="rounded-lg border border-gray-200 p-4 text-black hover:bg-gray-50">
                <div class="font-bold">Registrations</div>
                <p class="mt-1 text-sm text-gray-700">Confirm riders, manage waiting list, update payment status.</p>
            </a>
            <a href="{{ route('filament.admin.resources.packages.index') }}" class="rounded-lg border border-gray-200 p-4 text-black hover:bg-gray-50">
                <div class="font-bold">Packages</div>
                <p class="mt-1 text-sm text-gray-700">Full Package, Independent, Spectator and included features.</p>
            </a>
            <a href="{{ route('filament.admin.resources.tire-requests.index') }}" class="rounded-lg border border-gray-200 p-4 text-black hover:bg-gray-50">
                <div class="font-bold">Tire service</div>
                <p class="mt-1 text-sm text-gray-700">Own tire sets, Pirelli orders, service status.</p>
            </a>
            <a href="{{ route('filament.admin.resources.programme-items.index') }}" class="rounded-lg border border-gray-200 p-4 text-black hover:bg-gray-50">
                <div class="font-bold">Programme</div>
                <p class="mt-1 text-sm text-gray-700">Travel days, track sessions, briefings, meals.</p>
            </a>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-lg bg-gray-50 p-4">
                <div class="font-bold text-black">Hotel</div>
                <p class="mt-1 text-sm text-gray-700">{{ $hotelName }} · rooming, bus groups and travel info.</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-4">
                <div class="font-bold text-black">Motorcycles</div>
                <p class="mt-1 text-sm text-gray-700">Transport, plates, VIN, tire sizes and rider notes.</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-4">
                <div class="font-bold text-black">Mail</div>
                <p class="mt-1 text-sm text-gray-700">Onboarding, payment reminders and practical info templates.</p>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
