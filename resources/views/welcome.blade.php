<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Speedweek</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100">
    <main class="flex min-h-screen items-center justify-center px-6 py-16">
        <div class="flex flex-col items-center gap-8">
            <img src="/images/logo_25_meetthespeed_edited_edited.avif" alt="Speedweek" class="h-24 w-auto object-contain sm:h-32">
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a class="rounded-full bg-[#00d1c1] px-5 py-3 font-bold text-black" href="{{ route('login') }}">Login</a>
                <a class="rounded-full bg-[#00d1c1] px-5 py-3 font-bold text-black" href="{{ $event ? route('register', ['event' => $event->slug]) : route('register') }}">Registreer</a>
            </div>
        </div>
    </main>
</body>
</html>
