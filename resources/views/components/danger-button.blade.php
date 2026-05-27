<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#d0362e] border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#d0362e] active:bg-[#d0362e] focus:outline-none focus:ring-2 focus:ring-[#d0362e] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
