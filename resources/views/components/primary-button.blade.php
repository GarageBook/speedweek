<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#00d1c1] border border-transparent rounded-full font-bold text-xs text-black uppercase tracking-widest hover:bg-[#00d1c1] focus:bg-[#00d1c1] active:bg-[#00d1c1] focus:outline-none focus:ring-2 focus:ring-[#00d1c1] focus:ring-offset-2 focus:ring-offset-zinc-950 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
