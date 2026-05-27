@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-speedweek-500 text-start text-base font-bold text-speedweek-400 bg-zinc-900 focus:outline-none focus:text-speedweek-300 focus:bg-zinc-900 focus:border-speedweek-600 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-bold text-zinc-300 hover:text-speedweek-400 hover:bg-zinc-900 hover:border-speedweek-500 focus:outline-none focus:text-speedweek-400 focus:bg-zinc-900 focus:border-speedweek-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
