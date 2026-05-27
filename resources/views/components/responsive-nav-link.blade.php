@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#d0362e] text-start text-base font-bold text-[#d0362e] bg-zinc-900 focus:outline-none focus:text-[#d0362e] focus:bg-zinc-900 focus:border-[#d0362e] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-bold text-zinc-300 hover:text-[#d0362e] hover:bg-zinc-900 hover:border-[#d0362e] focus:outline-none focus:text-[#d0362e] focus:bg-zinc-900 focus:border-[#d0362e] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
