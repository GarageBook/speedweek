<x-speedweek-layout><x-slot:title>Track results</x-slot:title>
<div class="space-y-4" x-data="{ activeTab: 'uitslag' }">
    <section class="rounded bg-zinc-900 p-6 border border-zinc-800">
        <h3 class="dashboard-title text-2xl font-bold text-[#d0362e]">{{ $trackdayTitle }}</h3>
        <p class="mt-2 text-zinc-300">Rondetijden per sessie. De uitslag is gekoppeld aan geregistreerde deelnemers en hun motor en de tijden worden nu persistent in de database bewaard.</p>
    </section>

    <section class="rounded bg-zinc-900 p-5 border border-zinc-800">
        <div class="flex flex-wrap gap-2 border-b border-zinc-800 pb-4">
            <button
                type="button"
                class="rounded px-4 py-2 text-sm font-semibold whitespace-nowrap transition"
                :class="activeTab === 'uitslag' ? 'bg-[#d0362e] text-white' : 'bg-zinc-800 text-zinc-200 hover:bg-zinc-700'"
                @click="activeTab = 'uitslag'"
            >
                Uitslag
            </button>
            <button
                type="button"
                class="rounded px-4 py-2 text-sm font-semibold whitespace-nowrap transition"
                :class="activeTab === 'eigen-rondetijden' ? 'bg-[#d0362e] text-white' : 'bg-zinc-800 text-zinc-200 hover:bg-zinc-700'"
                @click="activeTab = 'eigen-rondetijden'"
            >
                Eigen rondetijden
            </button>
        </div>

        <div class="mt-4 space-y-4" x-show="activeTab === 'uitslag'" x-cloak>
            @foreach($sessions as $session)
                <section class="rounded border border-zinc-800 bg-zinc-900/50 p-4">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h4 class="font-semibold text-[#d0362e]">{{ $session['session_label'] }}</h4>
                            <p class="text-sm text-zinc-400">{{ $session['weather'] }}</p>
                        </div>
                    </div>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-zinc-400">
                                <tr>
                                    <th class="py-2 pr-4 whitespace-nowrap">#</th>
                                    <th class="py-2 pr-4 whitespace-nowrap">Rijder</th>
                                    <th class="py-2 pr-4 whitespace-nowrap">Motor</th>
                                    <th class="py-2 pr-4 whitespace-nowrap">Beste ronde</th>
                                    <th class="py-2 whitespace-nowrap">Verschil</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800">
                                @foreach($session['laps'] as $lap)
                                    <tr>
                                        <td class="py-3 pr-4 font-bold whitespace-nowrap">{{ $lap['position'] }}</td>
                                        <td class="py-3 pr-4 whitespace-nowrap">{{ $lap['rider'] }}</td>
                                        <td class="py-3 pr-4 text-zinc-300 whitespace-nowrap">{{ $lap['bike'] }}</td>
                                        <td class="py-3 pr-4 font-bold whitespace-nowrap">{{ $lap['lap'] }}</td>
                                        <td class="py-3 text-zinc-300 whitespace-nowrap">{{ $lap['gap'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endforeach
        </div>

        <div class="mt-4 space-y-4" x-show="activeTab === 'eigen-rondetijden'" x-cloak>
            @foreach($personalLapTimes as $session)
                <section class="rounded border border-zinc-800 bg-zinc-900/50 p-4">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h4 class="font-semibold text-[#d0362e]">{{ $session['session_label'] }}</h4>
                            <p class="text-sm text-zinc-400">{{ $session['weather'] }}</p>
                        </div>
                    </div>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-zinc-400">
                                <tr>
                                    <th class="py-2 pr-4 whitespace-nowrap">Rijder</th>
                                    <th class="py-2 pr-4 whitespace-nowrap">Motor</th>
                                    <th class="py-2 pr-4 whitespace-nowrap">Beste ronde</th>
                                    <th class="py-2 whitespace-nowrap">Positie sessie</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800">
                                @forelse($session['laps'] as $lap)
                                    <tr>
                                        <td class="py-3 pr-4 whitespace-nowrap">{{ $lap['rider'] }}</td>
                                        <td class="py-3 pr-4 text-zinc-300 whitespace-nowrap">{{ $lap['bike'] }}</td>
                                        <td class="py-3 pr-4 font-bold whitespace-nowrap">{{ $lap['lap'] }}</td>
                                        <td class="py-3 text-zinc-300 whitespace-nowrap">{{ $lap['position'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-3 text-zinc-400">Nog geen rondetijden beschikbaar voor deze sessie.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            @endforeach
        </div>
    </section>
</div>
</x-speedweek-layout>
