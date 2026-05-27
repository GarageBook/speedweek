@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#d0362e] text-sm font-bold leading-5 text-zinc-100 focus:outline-none focus:border-[#d0362e] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-bold leading-5 text-zinc-300 hover:text-[#d0362e] hover:border-[#d0362e] focus:outline-none focus:text-[#d0362e] focus:border-[#d0362e] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
