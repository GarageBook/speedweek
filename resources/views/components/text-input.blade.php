@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#d0362e] focus:ring-[#d0362e] rounded-md shadow-sm']) }}>
