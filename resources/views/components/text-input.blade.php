@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 bg-white text-black placeholder-gray-500 focus:border-[#d0362e] focus:ring-[#d0362e] rounded-md shadow-sm']) }}>
