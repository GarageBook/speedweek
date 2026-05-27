<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#d0362e] border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-[#d0362e] focus:bg-[#d0362e] active:bg-[#d0362e] focus:outline-none focus:ring-2 focus:ring-[#d0362e] focus:ring-offset-2 focus:ring-offset-zinc-950 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
