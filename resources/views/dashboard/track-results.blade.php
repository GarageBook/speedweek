<x-speedweek-layout><x-slot:title>Track results</x-slot:title>
<div class="space-y-4">
    <section class="rounded bg-zinc-900 p-6 border border-zinc-800">
        <h3 class="text-2xl font-bold text-[#d0362e]">Track results</h3>
        <p class="mt-2 text-zinc-300">Rondetijden per sessie. Live timing kan later worden gekoppeld; deze demo geeft alvast de verwachte weergave.</p>
    </section>

    @foreach($sessions as $session)
        <section class="rounded bg-zinc-900 p-5 border border-zinc-800">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h4 class="font-semibold text-[#d0362e]">{{ $session['name'] }}</h4>
                    <p class="text-sm text-zinc-400">{{ $session['date'] }} · {{ $session['weather'] }}</p>
                </div>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-zinc-400">
                        <tr>
                            <th class="py-2 pr-4">#</th>
                            <th class="py-2 pr-4">Rijder</th>
                            <th class="py-2 pr-4">Motor</th>
                            <th class="py-2 pr-4">Beste ronde</th>
                            <th class="py-2">Verschil</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @foreach($session['laps'] as $lap)
                            <tr>
                                <td class="py-3 pr-4 font-bold">{{ $lap['position'] }}</td>
                                <td class="py-3 pr-4">{{ $lap['rider'] }}</td>
                                <td class="py-3 pr-4 text-zinc-300">{{ $lap['bike'] }}</td>
                                <td class="py-3 pr-4 font-bold">{{ $lap['lap'] }}</td>
                                <td class="py-3 text-zinc-300">{{ $lap['gap'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endforeach
</div>
</x-speedweek-layout>
