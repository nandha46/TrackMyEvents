<nav x-data="{ open: false, userMenuOpen: false }" class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200/80 dark:border-gray-800 sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-4 sm:gap-6">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <img class="block h-9 w-auto rounded-lg shadow-xs" src="{{ url('/logo_tme.png') }}" alt="TrackMyEvents">
                        <span class="hidden sm:inline-block font-black text-lg text-gray-900 dark:text-white tracking-tight">TrackMy<span class="text-indigo-600 dark:text-indigo-400">Events</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <!-- Navigation Links (Tags based) -->
                <div class="hidden lg:flex items-center space-x-1 sm:-my-px">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 text-xs font-extrabold rounded-xl transition {{ request()->routeIs('dashboard') && !request('tag') && !request('filter') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        All Events
                    </a>
                    <a href="{{ route('dashboard', ['tag' => 'Birthday']) }}" class="px-3 py-2 text-xs font-bold rounded-xl transition {{ request('tag') === 'Birthday' ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        🎂 #Birthday
                    </a>
                    <a href="{{ route('dashboard', ['tag' => 'Personal']) }}" class="px-3 py-2 text-xs font-bold rounded-xl transition {{ request('tag') === 'Personal' ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        #Personal
                    </a>
                    <a href="{{ route('dashboard', ['tag' => 'Work']) }}" class="px-3 py-2 text-xs font-bold rounded-xl transition {{ request('tag') === 'Work' ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        #Work
                    </a>
                    <a href="{{ route('dashboard', ['tag' => 'Travel']) }}" class="px-3 py-2 text-xs font-bold rounded-xl transition {{ request('tag') === 'Travel' ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        ✈️ #Travel
                    </a>
                </div>
            </div>

            <!-- Search Bar (Desktop) -->
            <div class="hidden md:flex items-center flex-1 max-w-xs mx-4 lg:mx-6">
                <form action="{{ route('dashboard') }}" method="GET" class="w-full relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events or #tags... ('/')"
                           class="w-full pl-9 pr-4 py-2 text-xs rounded-2xl bg-gray-100 dark:bg-gray-800 border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-gray-900 focus:ring-0 text-gray-800 dark:text-gray-200 placeholder-gray-400 transition">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </form>
            </div>

            <!-- Right Controls: Private Lock Toggle, New Event, Dark Mode, Shortcuts & User Menu -->
            <div class="hidden sm:flex sm:items-center sm:gap-2">
                <!-- Private Events Lock/Unlock Toggle Button -->
                <button type="button" @click="$store.privateLock.toggleLock()" 
                        :title="$store.privateLock.isUnlocked ? 'Private events unlocked (Click to lock). Auto-locks in ' + $store.privateLock.formattedRemaining : 'Private events locked (Click to unlock with 4-digit PIN)'"
                        :class="$store.privateLock.isUnlocked ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-700' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-gray-700 dark:text-gray-300 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 transition">
                    <template x-if="!$store.privateLock.isUnlocked">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <span class="hidden md:inline">Locked</span>
                        </span>
                    </template>
                    <template x-if="$store.privateLock.isUnlocked">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                            <span class="font-mono text-[11px]" x-text="$store.privateLock.formattedRemaining"></span>
                        </span>
                    </template>
                </button>

                <!-- Add Event CTA -->
                <a href="{{ route('event.create') }}" id="new-event-btn" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-xs font-black shadow-sm transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    New Event
                </a>

                <!-- Dark Mode Toggle Button -->
                <button type="button" onclick="window.toggleDarkMode()" class="p-2 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition" title="Toggle Dark Mode">
                    <svg class="w-4 h-4 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <svg class="w-4 h-4 block dark:hidden text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </button>

                <!-- Keyboard Shortcuts Help Button -->
                <button type="button" @click="$dispatch('toggle-shortcuts-help')" class="p-2 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition font-mono text-xs font-bold" title="Keyboard Shortcuts (?)">
                    ?
                </button>

                <!-- User Dropdown Menu -->
                <div class="relative ml-2">
                    <button type="button" @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-2 text-xs font-bold text-gray-700 dark:text-gray-300 p-1.5 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <img class="w-8 h-8 rounded-xl object-cover ring-2 ring-indigo-500/40" src="{{ asset('storage/avatars/'.(Auth::user()->avatar ?? 'default.png')) }}" alt="{{ Auth::user()->name ?? 'User' }}">
                        <span class="max-w-[100px] truncate hidden md:inline">{{ Auth::user()->name ?? 'Account' }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="userMenuOpen" @click.away="userMenuOpen = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl py-2 border border-gray-100 dark:border-gray-700 text-xs z-50">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                            <p class="font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 font-semibold">Profile & Settings</a>
                        <a href="{{ route('profile.pexels_stats') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 font-semibold">Pexels Rate Limits</a>
                        <a href="{{ url('/pulse') }}" target="_blank" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 font-semibold">Pulse Dashboard &nearr;</a>
                        <a href="{{ url('/telescope') }}" target="_blank" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 font-semibold">Telescope &nearr;</a>
                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-2xl text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': ! open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Drawer Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="p-4">
            <form action="{{ route('dashboard') }}" method="GET" class="w-full relative mb-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..."
                       class="w-full pl-9 pr-4 py-2.5 text-xs rounded-2xl bg-gray-100 dark:bg-gray-800 border-transparent text-gray-800 dark:text-gray-200">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>

            <a href="{{ route('event.create') }}" class="block w-full text-center py-3 bg-indigo-600 text-white rounded-2xl text-xs font-black shadow-md mb-3">
                + Create New Event
            </a>
        </div>

        <div class="pt-2 pb-3 space-y-1 px-2">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('dashboard') && !request('tag') && !request('filter') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-950/40' : 'text-gray-600 dark:text-gray-300' }}">All Events</a>
            <a href="{{ route('dashboard', ['tag' => 'Birthday']) }}" class="block px-3 py-2 rounded-xl text-xs font-bold {{ request('tag') === 'Birthday' ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-950/40' : 'text-gray-600 dark:text-gray-300' }}">🎂 #Birthday</a>
            <a href="{{ route('dashboard', ['tag' => 'Personal']) }}" class="block px-3 py-2 rounded-xl text-xs font-bold {{ request('tag') === 'Personal' ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-950/40' : 'text-gray-600 dark:text-gray-300' }}">#Personal</a>
            <a href="{{ route('dashboard', ['tag' => 'Work']) }}" class="block px-3 py-2 rounded-xl text-xs font-bold {{ request('tag') === 'Work' ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-950/40' : 'text-gray-600 dark:text-gray-300' }}">#Work</a>
            <a href="{{ route('dashboard', ['tag' => 'Travel']) }}" class="block px-3 py-2 rounded-xl text-xs font-bold {{ request('tag') === 'Travel' ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-950/40' : 'text-gray-600 dark:text-gray-300' }}">✈️ #Travel</a>
        </div>

        <div class="pt-4 pb-4 border-t border-gray-200 dark:border-gray-800 px-4">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <img class="w-9 h-9 rounded-xl object-cover" src="{{ asset('storage/avatars/'.(Auth::user()->avatar ?? 'default.png')) }}" alt="{{ Auth::user()->name ?? 'User' }}">
                    <div>
                        <div class="font-bold text-xs text-gray-800 dark:text-gray-200">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="text-[10px] text-gray-500">{{ Auth::user()->email ?? '' }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="$store.privateLock.toggleLock()" class="p-2 rounded-xl text-xs font-bold"
                            :class="$store.privateLock.isUnlocked ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'">
                        <span x-text="$store.privateLock.isUnlocked ? '🔓 ' + $store.privateLock.formattedRemaining : '🔒 Lock'"></span>
                    </button>
                    <button type="button" onclick="window.toggleDarkMode()" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-xs font-bold">
                        🌓 Mode
                    </button>
                </div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}" class="block py-2 text-xs text-gray-600 dark:text-gray-300 font-semibold">Profile & Settings</a>
                <a href="{{ route('profile.pexels_stats') }}" class="block py-2 text-xs text-gray-600 dark:text-gray-300 font-semibold">Pexels Rate Limits</a>
                <a href="{{ url('/pulse') }}" target="_blank" class="block py-2 text-xs text-gray-600 dark:text-gray-300 font-semibold">Pulse Dashboard &nearr;</a>
                <a href="{{ url('/telescope') }}" target="_blank" class="block py-2 text-xs text-gray-600 dark:text-gray-300 font-semibold">Telescope &nearr;</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2 text-xs text-rose-600 font-bold">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
