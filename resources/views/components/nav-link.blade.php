@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-speedweek-500 text-sm font-bold leading-5 text-zinc-100 focus:outline-none focus:border-speedweek-600 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-bold leading-5 text-zinc-300 hover:text-speedweek-400 hover:border-speedweek-500 focus:outline-none focus:text-speedweek-400 focus:border-speedweek-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
