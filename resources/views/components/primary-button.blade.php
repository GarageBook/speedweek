<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-speedweek-500 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-speedweek-600 focus:bg-speedweek-600 active:bg-speedweek-700 focus:outline-none focus:ring-2 focus:ring-speedweek-500 focus:ring-offset-2 focus:ring-offset-zinc-950 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
