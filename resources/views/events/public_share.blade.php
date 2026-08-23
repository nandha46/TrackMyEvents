<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $event->event_name }} - Countdown | TrackMyEvents</title>
    
    <!-- OpenGraph & Twitter Meta Tags -->
    <meta property="og:title" content="{{ $event->event_name }} - Live Countdown">
    <meta property="og:description" content="{{ $event->description ?? 'Track this moment live with TrackMyEvents.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $event->event_name }} - Live Countdown">
    <meta name="twitter:description" content="{{ $event->description ?? 'Track this moment live with TrackMyEvents.' }}">

    <!-- Script for Dark Mode -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-950 min-h-screen flex flex-col justify-between">

    <!-- Top minimal bar -->
    <header class="w-full px-6 py-4 flex justify-between items-center bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-800">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <img class="h-8 w-auto rounded" src="{{ url('/logo_tme.png') }}" alt="TrackMyEvents">
            <span class="text-gray-900 dark:text-white font-bold text-lg">TrackMy<span class="text-indigo-600 dark:text-indigo-400">Events</span></span>
        </a>
        <div class="flex items-center gap-3">
            <button type="button" onclick="window.toggleDarkMode()" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                <svg class="h-4 w-4 hidden dark:block text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <svg class="h-4 w-4 block dark:hidden text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
            </button>
            <a href="{{ route('register') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-xs transition">
                Create Free Account
            </a>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 my-6">
        <div class="max-w-2xl w-full bg-white dark:bg-gray-900 rounded-3xl shadow-2xl overflow-hidden border border-gray-200/80 dark:border-gray-800">
            
            <!-- Banner / Gradient -->
            @if($event->is_background_image)
              <div class="relative w-full h-64 bg-gray-900">
                <img src="{{ asset('uploads/backgrounds/'.$event->background) }}" alt="{{ $event->event_name }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-900/50 to-transparent flex flex-col justify-end p-6 text-white">
                  <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-wider w-max mb-2">{{ $event->event_type }}</span>
                  <h1 class="text-2xl sm:text-3xl font-extrabold">{{ $event->event_name }}</h1>
                  <p class="text-xs text-gray-300">{{ $event->event_date }} &bull; {{ $event->event_time ? date('h:i A', strtotime($event->event_time)) : 'All Day' }}</p>
                </div>
              </div>
            @else
              <div class="w-full h-56 bg-gradient-to-r {{ $event->getGradientClasses() }} flex flex-col justify-end p-6 text-white">
                <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-wider w-max mb-2">{{ $event->event_type }}</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold">{{ $event->event_name }}</h1>
                <p class="text-xs text-white/80">{{ $event->event_date }} &bull; {{ $event->event_time ? date('h:i A', strtotime($event->event_time)) : 'All Day' }}</p>
              </div>
            @endif

            <div class="p-6 sm:p-8">
              <!-- Live Real-Time Ticking Countdown Box -->
              <div x-data="countdown('{{ $event->getTargetDateTime()->format('Y-m-d') }}', '{{ $event->getTargetDateTime()->format('H:i:s') }}', {{ $event->isPastEvent() ? 'true' : 'false' }})" 
                   class="bg-gray-50 dark:bg-gray-800/60 rounded-2xl p-6 mb-6 text-center border border-gray-100 dark:border-gray-700/60">
                <p class="text-xs uppercase tracking-widest text-indigo-600 dark:text-indigo-400 font-bold mb-4">
                  <span x-show="!isPast">COUNTDOWN TO EVENT</span>
                  <span x-show="isPast" x-cloak>TIME SINCE EVENT</span>
                </p>
                
                @php
                  $selectedFields = $event->getSelectedFieldsArray();
                @endphp
                <div class="flex flex-wrap justify-center gap-2.5 max-w-lg mx-auto text-center">
                  @if(in_array('years', $selectedFields))
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700 min-w-[70px] flex-1">
                      <span class="block text-2xl sm:text-3xl font-black text-indigo-600 dark:text-indigo-400 font-mono" x-text="years"></span>
                      <span class="text-[10px] font-semibold text-gray-500 uppercase">Years</span>
                    </div>
                  @endif
                  @if(in_array('months', $selectedFields))
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700 min-w-[70px] flex-1">
                      <span class="block text-2xl sm:text-3xl font-black text-indigo-600 dark:text-indigo-400 font-mono" x-text="months"></span>
                      <span class="text-[10px] font-semibold text-gray-500 uppercase">Months</span>
                    </div>
                  @endif
                  @if(in_array('weeks', $selectedFields) && !in_array('days', $selectedFields))
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700 min-w-[70px] flex-1">
                      <span class="block text-2xl sm:text-3xl font-black text-indigo-600 dark:text-indigo-400 font-mono" x-text="weeks"></span>
                      <span class="text-[10px] font-semibold text-gray-500 uppercase">Weeks</span>
                    </div>
                  @endif
                  @if(in_array('days', $selectedFields))
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700 min-w-[70px] flex-1">
                      <span class="block text-2xl sm:text-3xl font-black text-indigo-600 dark:text-indigo-400 font-mono" x-text="days"></span>
                      <span class="text-[10px] font-semibold text-gray-500 uppercase">Days</span>
                    </div>
                  @endif
                  @if(in_array('hours', $selectedFields))
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700 min-w-[70px] flex-1">
                      <span class="block text-2xl sm:text-3xl font-black text-gray-900 dark:text-white font-mono" x-text="String(hours).padStart(2, '0')"></span>
                      <span class="text-[10px] font-semibold text-gray-500 uppercase">Hours</span>
                    </div>
                  @endif
                  @if(in_array('minutes', $selectedFields))
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700 min-w-[70px] flex-1">
                      <span class="block text-2xl sm:text-3xl font-black text-gray-900 dark:text-white font-mono" x-text="String(minutes).padStart(2, '0')"></span>
                      <span class="text-[10px] font-semibold text-gray-500 uppercase">Mins</span>
                    </div>
                  @endif
                  @if(in_array('seconds', $selectedFields))
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700 min-w-[70px] flex-1">
                      <span class="block text-2xl sm:text-3xl font-black text-rose-500 font-mono animate-pulse" x-text="String(seconds).padStart(2, '0')"></span>
                      <span class="text-[10px] font-semibold text-gray-500 uppercase">Secs</span>
                    </div>
                  @endif
                </div>
              </div>

              @if($event->description)
                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800/40 rounded-xl border border-gray-100 dark:border-gray-800">
                  <p class="text-sm text-gray-700 dark:text-gray-300">{{ $event->description }}</p>
                </div>
              @endif

              <!-- Actions -->
              <div class="flex flex-wrap gap-2 justify-center">
                <a href="{{ $event->google_calendar_url }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 rounded-xl text-xs font-bold border border-indigo-200 dark:border-indigo-800 hover:bg-indigo-100 transition">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                  Add to Google Calendar
                </a>
                <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Event link copied to clipboard!')"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                  Share Link
                </button>
              </div>
            </div>

            <!-- Call to action footer -->
            <div class="bg-gray-50 dark:bg-gray-800/80 p-6 text-center border-t border-gray-100 dark:border-gray-800">
              <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Track your own life events & milestones</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Countdowns, recurring milestones, reminders, and custom card widgets.</p>
              <a href="{{ route('register') }}" class="inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow-md transition">
                Start Tracking Free &rarr;
              </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-4 text-xs text-gray-500 dark:text-gray-400">
        &copy; {{ date('Y') }} TrackMyEvents. All rights reserved.
    </footer>
</body>
</html>
