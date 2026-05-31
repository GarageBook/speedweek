<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">{{ $title ?? 'Speedweek' }}</h2></x-slot>
<div class="min-h-screen bg-zinc-950 text-zinc-100 py-8"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">{{ $slot }}</div></div>
</x-app-layout>
