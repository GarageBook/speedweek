<x-speedweek-layout>
    <x-slot:title>Register event</x-slot:title>

    <div class="rounded bg-zinc-900 p-6 border border-zinc-800">
        <h3 class="text-xl font-bold text-[#d0362e]">{{ $event->name }}</h3>

        <form method="post" class="mt-4 space-y-5" x-data="{ hasLicensePlate: @js((bool) old('has_license_plate')) }">
            @csrf

            <div class="grid gap-3 md:grid-cols-3">
                @foreach($event->packages as $package)
                    <label class="block rounded border border-zinc-700 p-4">
                        <input type="radio" name="package_id" value="{{ $package->id }}" required @checked(old('package_id') == $package->id)>
                        <strong>{{ $package->name }}</strong><br>
                        <span class="text-sm">EUR {{ number_format($package->price_cents/100, 2) }}</span>
                    </label>
                @endforeach
            </div>

            <label class="block">
                <input type="checkbox" name="single_room_requested" value="1" @checked(old('single_room_requested'))>
                Single room (+ EUR 250)
            </label>

            <input class="w-full rounded bg-zinc-950 border-zinc-700" name="roommate_preference" placeholder="Roommate preference" value="{{ old('roommate_preference') }}">

            <label class="block">
                <input type="checkbox" name="checked_luggage_requested" value="1" @checked(old('checked_luggage_requested'))>
                Checked luggage
            </label>

            <section class="rounded border border-zinc-800 p-4 space-y-4">
                <h4 class="font-semibold text-[#d0362e]">Motorgegevens</h4>

                <div>
                    <label class="block text-sm mb-1">Merk *</label>
                    <input class="w-full rounded bg-zinc-950 border-zinc-700" name="motorcycle_brand" required value="{{ old('motorcycle_brand') }}">
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm mb-1">Type / Model</label>
                        <input class="w-full rounded bg-zinc-950 border-zinc-700" name="motorcycle_model" value="{{ old('motorcycle_model') }}">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Bouwjaar</label>
                        <input type="number" min="1900" max="2100" class="w-full rounded bg-zinc-950 border-zinc-700" name="motorcycle_year" value="{{ old('motorcycle_year') }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-sm">Kenteken aanwezig?</p>
                    <label class="inline-flex items-center mr-4">
                        <input type="radio" name="has_license_plate" value="1" x-model="hasLicensePlate">
                        <span class="ml-2">Ja</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="has_license_plate" value="0" x-model="hasLicensePlate">
                        <span class="ml-2">Nee</span>
                    </label>
                </div>

                <div x-show="hasLicensePlate" x-cloak>
                    <label class="block text-sm mb-1">Kenteken</label>
                    <input class="w-full rounded bg-zinc-950 border-zinc-700" name="motorcycle_license_plate" value="{{ old('motorcycle_license_plate') }}">
                </div>

                <div>
                    <label class="block text-sm mb-1">Bijzonderheden</label>
                    <textarea class="w-full rounded bg-zinc-950 border-zinc-700" rows="4" name="motorcycle_notes">{{ old('motorcycle_notes') }}</textarea>
                </div>
            </section>

            <button class="rounded-full bg-[#00d1c1] px-4 py-2 font-bold text-black">Continue</button>
        </form>
    </div>
</x-speedweek-layout>
