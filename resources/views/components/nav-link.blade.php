@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#00d1c1] text-sm font-bold leading-5 text-zinc-100 focus:outline-none focus:border-[#00d1c1] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-bold leading-5 text-zinc-300 hover:text-[#00d1c1] hover:border-[#00d1c1] focus:outline-none focus:text-[#00d1c1] focus:border-[#00d1c1] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
