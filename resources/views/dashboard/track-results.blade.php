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
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h4 class="font-semibold text-[#d0362e]">{{ $session['session_label'] }}</h4>
                            <p class="text-sm text-zinc-400">{{ $session['weather'] }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-zinc-800 px-3 py-1 text-xs font-medium text-zinc-200">{{ $session['focus'] }}</span>
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
            <section class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                <article class="rounded border border-zinc-800 bg-zinc-900/60 p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Beste ronde</p>
                    <p class="mt-2 text-2xl font-bold text-white">{{ $personalOverview['best_lap'] }}</p>
                </article>
                <article class="rounded border border-zinc-800 bg-zinc-900/60 p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Gemiddelde</p>
                    <p class="mt-2 text-2xl font-bold text-white">{{ $personalOverview['average_lap'] }}</p>
                </article>
                <article class="rounded border border-zinc-800 bg-zinc-900/60 p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Verbetering dag</p>
                    <p class="mt-2 text-2xl font-bold text-white">{{ $personalOverview['improvement'] }}</p>
                </article>
                <article class="rounded border border-zinc-800 bg-zinc-900/60 p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Sessies / rondes</p>
                    <p class="mt-2 text-2xl font-bold text-white">{{ $personalOverview['session_count'] }} / {{ $personalOverview['lap_count'] }}</p>
                </article>
            </section>

            @foreach($personalLapTimes as $session)
                <section class="rounded border border-zinc-800 bg-zinc-900/50 p-4">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="font-semibold text-[#d0362e]">{{ $session['session_label'] }}</h4>
                                <span class="inline-flex items-center rounded-full bg-zinc-800 px-3 py-1 text-xs font-medium text-zinc-200">{{ $session['focus'] }}</span>
                            </div>
                            <p class="mt-1 text-sm text-zinc-400">{{ $session['weather'] }}</p>
                            <p class="mt-2 text-sm text-zinc-300">{{ $session['rider'] ?? 'Rijder' }} • {{ $session['bike'] ?? 'Motor' }}</p>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4 xl:text-right">
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Beste ronde</p>
                                <p class="mt-1 text-lg font-bold text-white">{{ $session['best_lap'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Gemiddelde</p>
                                <p class="mt-1 text-lg font-bold text-white">{{ $session['average_lap'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Verbetering</p>
                                <p class="mt-1 text-lg font-bold text-white">{{ $session['improvement'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Rondes</p>
                                <p class="mt-1 text-lg font-bold text-white">{{ $session['lap_count'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-zinc-400">
                                <tr>
                                    <th class="py-2 pr-4 whitespace-nowrap">Ronde</th>
                                    <th class="py-2 pr-4 whitespace-nowrap">Rondetijd</th>
                                    <th class="py-2 pr-4 whitespace-nowrap">Verschil</th>
                                    <th class="py-2 pr-4 whitespace-nowrap">Trend</th>
                                    <th class="py-2 whitespace-nowrap">Notitie</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800">
                                @forelse($session['laps'] as $lap)
                                    <tr class="{{ $lap['is_best'] ? 'bg-emerald-500/10' : '' }}">
                                        <td class="py-3 pr-4 font-bold whitespace-nowrap">{{ $lap['lap_number'] }}</td>
                                        <td class="py-3 pr-4 whitespace-nowrap {{ $lap['is_best'] ? 'font-bold text-emerald-300' : 'font-semibold text-white' }}">{{ $lap['lap'] }}</td>
                                        <td class="py-3 pr-4 whitespace-nowrap text-zinc-300">{{ $lap['delta_to_best'] }}</td>
                                        <td class="py-3 pr-4 whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $lap['trend_class'] }}">{{ $lap['trend_label'] }}</span>
                                        </td>
                                        <td class="py-3 text-zinc-300 min-w-[18rem]">{{ $lap['note'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-3 text-zinc-400">Nog geen rondetijden beschikbaar voor deze sessie.</td>
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