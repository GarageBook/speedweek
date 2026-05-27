@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#00d1c1] text-start text-base font-bold text-[#00d1c1] bg-zinc-900 focus:outline-none focus:text-[#00d1c1] focus:bg-zinc-900 focus:border-[#00d1c1] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-bold text-zinc-300 hover:text-[#00d1c1] hover:bg-zinc-900 hover:border-[#00d1c1] focus:outline-none focus:text-[#00d1c1] focus:bg-zinc-900 focus:border-[#00d1c1] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
