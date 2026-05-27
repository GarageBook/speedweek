@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 bg-white text-black placeholder-gray-500 focus:border-[#00d1c1] focus:ring-[#00d1c1] rounded-md shadow-sm']) }}>
