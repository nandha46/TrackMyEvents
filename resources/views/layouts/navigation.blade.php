<nav class="bg-gray-800" x-data="{ mobileMenuOpen: false, profileMenuOpen: false }">
  <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    <div class="relative flex h-16 items-center justify-between">
      <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
        <!-- Mobile menu button-->
        <button type="button"
          class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
          @click="mobileMenuOpen = !mobileMenuOpen" type="button"
          class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
          aria-controls="mobile-menu" aria-expanded="false">
          <span class="absolute -inset-0.5"></span>
          <span class="sr-only">Open main menu</span>
          <!--
              Icon when menu is closed.
  
              Menu open: "hidden", Menu closed: "block"
            -->
          <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            aria-hidden="true" data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
          <!--
              Icon when menu is open.
  
              Menu open: "block", Menu closed: "hidden"
            -->
          <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            aria-hidden="true" data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
        <div class="flex flex-shrink-0 items-center">
          <img class="h-8 w-auto" src="{{ url("/logo_tme.png") }}" alt="Your Company">
        </div>
        <div class="hidden sm:ml-6 sm:block">
          <div class="flex space-x-2">
            <a href="{{ route('dashboard') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ !request('filter') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">All</a>
            <a href="{{ route('dashboard', ['filter' => 'Personal']) }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request('filter') === 'Personal' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Personal</a>
            <a href="{{ route('dashboard', ['filter' => 'Work']) }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request('filter') === 'Work' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Work</a>
            <a href="{{ route('dashboard', ['filter' => 'Reminder']) }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request('filter') === 'Reminder' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Reminder</a>
            <a href="{{ route('dashboard', ['filter' => 'Past']) }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request('filter') === 'Past' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Past</a>
            <a href="{{ route('dashboard', ['filter' => 'Future']) }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request('filter') === 'Future' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Future</a>
            <a href="{{ url('/telescope') }}" target="_blank"
              class="flex items-center rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
              <span>Telescope</span>
            </a>
            <a href="{{ url('/pulse') }}" target="_blank"
              class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Pulse</a>
          </div>
        </div>
      </div>
      <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
        <div class="mr-4 hidden md:block">
          <form method="GET" action="{{ route('dashboard') }}">
            @if(request('filter'))
              <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            @if(request('view'))
              <input type="hidden" name="view" value="{{ request('view') }}">
            @endif
            <label class="relative block">
              <span class="sr-only">Search</span>
              <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-slate-400">
                <x-Icons.magnify />
              </span>
              <input
                class="placeholder:italic placeholder:text-slate-400 block bg-gray-700 text-white w-full border border-gray-600 rounded-md py-1.5 pl-9 pr-3 shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                placeholder="Search events..." type="text" name="search" value="{{ request('search') }}" />
            </label>
          </form>
        </div>
        <x-new-event-button href="{{ route('event.create') }}" class="hidden sm:block">
          New Event
        </x-new-event-button>
        <!-- Notifications Dropdown -->
        <div class="relative ml-2" x-data="{ notifOpen: false }">
          <button type="button" @click="notifOpen = !notifOpen"
            class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
            <span class="absolute -inset-1.5"></span>
            <span class="sr-only">View notifications</span>
            <x-Icons.bell />
            @if(Auth::user()->unreadNotifications->count() > 0)
              <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                {{ Auth::user()->unreadNotifications->count() }}
              </span>
            @endif
          </button>

          <div x-show="notifOpen" @click.away="notifOpen = false"
            class="absolute right-0 z-20 mt-2 w-72 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95" x-cloak>
            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
              <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Notifications</span>
              <span class="text-xs text-gray-500">{{ Auth::user()->notifications()->count() }} total</span>
            </div>
            <div class="max-h-60 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
              @forelse(Auth::user()->notifications()->latest()->take(5)->get() as $notification)
                <div class="px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                  <p class="font-medium text-gray-900 dark:text-gray-100">{{ $notification->data['message'] ?? 'Notification received' }}</p>
                  <span class="text-[10px] text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
              @empty
                <div class="px-4 py-3 text-xs text-gray-500 text-center">No notifications yet</div>
              @endforelse
            </div>
          </div>
        </div>

        <!-- Profile dropdown -->
        <div class="relative ml-3" x-data="{ profileMenuOpen: false }">
          <div>
            <button type="button" @click="profileMenuOpen = !profileMenuOpen"
              class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
              id="user-menu-button" aria-expanded="false" aria-haspopup="true">
              <span class="absolute -inset-1.5"></span>
              <span class="sr-only">Open user menu</span>
              <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('uploads/profile_pictures/'.Auth::user()->avatar) }}"
                alt="Profile picture">
            </button>
          </div>

          <div x-show="profileMenuOpen" @click.away="profileMenuOpen = false" x-cloak
            class="absolute right-0 z-20 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95">
            <a href="{{ route('profile.edit') }}"
              class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-500 hover:text-white" role="menuitem"
              tabindex="-1" id="user-menu-item-0">Your Profile</a>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-500 hover:text-white"
              role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>
            <form action="{{ route('logout') }}" method="POST" class="hover:bg-indigo-500">
              @csrf
              <button class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:text-white text-left w-full" type="submit">Sign
                out</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile menu, show/hide based on menu state. -->
  <div class="sm:hidden" id="mobile-menu" x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    <div class="space-y-1 px-2 pb-3 pt-2">
      <a href="{{ route('dashboard') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ !request('filter') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">All</a>
      <a href="{{ route('dashboard', ['filter' => 'Personal']) }}" class="block rounded-md px-3 py-2 text-base font-medium {{ request('filter') === 'Personal' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Personal</a>
      <a href="{{ route('dashboard', ['filter' => 'Work']) }}" class="block rounded-md px-3 py-2 text-base font-medium {{ request('filter') === 'Work' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Work</a>
      <a href="{{ route('dashboard', ['filter' => 'Reminder']) }}" class="block rounded-md px-3 py-2 text-base font-medium {{ request('filter') === 'Reminder' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Reminder</a>
      <a href="{{ route('dashboard', ['filter' => 'Past']) }}" class="block rounded-md px-3 py-2 text-base font-medium {{ request('filter') === 'Past' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Past</a>
      <a href="{{ route('dashboard', ['filter' => 'Future']) }}" class="block rounded-md px-3 py-2 text-base font-medium {{ request('filter') === 'Future' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Future</a>
    </div>
  </div>
</nav>