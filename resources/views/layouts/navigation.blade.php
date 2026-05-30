<nav
    x-data="{ open: false, scrolled: false, themeMode: document.documentElement.classList.contains('theme-light') ? 'light' : 'dark', toggleThemeMode() { window.toggleTheme(); this.themeMode = document.documentElement.classList.contains('theme-light') ? 'light' : 'dark'; } }"
    x-init="scrolled = window.scrollY > 8; window.addEventListener('scroll', () => { scrolled = window.scrollY > 8 }, { passive: true })"
    class="sticky top-0 z-50 nav-sticky border-b border-zinc-800/90"
    :class="scrolled ? 'bg-zinc-950/95 shadow-lg shadow-black/20 backdrop-blur-md' : 'bg-zinc-950/75 backdrop-blur-sm'"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-300" :class="scrolled ? 'py-1' : 'py-0'">
        <div class="flex justify-between h-16 transition-all duration-300" :class="scrolled ? 'h-14' : 'h-16'">
            <div class="flex min-w-0">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.track-results')" :active="request()->routeIs('dashboard.track-results')">
                        Track results
                    </x-nav-link>
                    @if(Auth::user()?->is_admin)
                        <x-nav-link :href="route('filament.admin.pages.dashboard')" :active="request()->is('admin*')">
                            Admin
                        </x-nav-link>
                        <x-nav-link :href="route('filament.admin.resources.users.index')" :active="request()->is('admin/users*')">
                            Gebruikers
                        </x-nav-link>
                        <x-nav-link :href="route('filament.admin.resources.invoices.index')" :active="request()->is('admin/invoices*')">
                            Finance
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-4">
                <button
                    type="button"
                    class="inline-flex items-center rounded-md border border-zinc-700 px-3 py-2 text-sm text-zinc-200 transition hover:border-zinc-500"
                    @click="toggleThemeMode()"
                >
                    <span x-text="`Weergave: ${themeMode === 'dark' ? 'Dark' : 'Light'}`"></span>
                </button>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-zinc-200 bg-zinc-950/80 hover:text-[#d0362e] focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profiel
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Uitloggen
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-zinc-300 hover:text-[#d0362e] hover:bg-zinc-900/90 focus:outline-none focus:bg-zinc-900 focus:text-[#d0362e] transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-zinc-800/80 bg-zinc-950/95 backdrop-blur-md">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard.track-results')" :active="request()->routeIs('dashboard.track-results')">
                Track results
            </x-responsive-nav-link>
            @if(Auth::user()?->is_admin)
                <x-responsive-nav-link :href="route('filament.admin.pages.dashboard')" :active="request()->is('admin*')">
                    Admin
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('filament.admin.resources.users.index')" :active="request()->is('admin/users*')">
                    Gebruikers
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('filament.admin.resources.invoices.index')" :active="request()->is('admin/invoices*')">
                    Finance
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="px-4 py-3 border-t border-zinc-800">
            <button
                type="button"
                class="inline-flex items-center rounded-md border border-zinc-700 px-3 py-2 text-sm text-zinc-200 transition hover:border-zinc-500"
                @click="toggleThemeMode()"
            >
                <span x-text="`Weergave: ${themeMode === 'dark' ? 'Dark' : 'Light'}`"></span>
            </button>
        </div>

        <div class="pt-4 pb-1 border-t border-zinc-800">
            <div class="px-4">
                <div class="font-medium text-base text-zinc-100">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-zinc-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profiel
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Uitloggen
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
