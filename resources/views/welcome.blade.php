<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TrackMyEvents — Track Life's Milestones, Goals & Real-Time Countdowns</title>
    <meta name="description" content="Track upcoming and past events with live ticking countdowns, recurring milestones, checklist progress, calendar export, and custom card widgets.">

    <!-- OpenGraph & Twitter Meta Tags -->
    <meta property="og:title" content="TrackMyEvents — Real-Time Milestone & Event Countdown Tracker">
    <meta property="og:description" content="Sleek, versatile countdown and milestone tracker for life, work, birthdays, travels, and projects.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="TrackMyEvents — Track Milestones & Countdowns">
    <meta name="twitter:description" content="Live ticking countdowns, recurrence, calendar sync, and interactive cards.">

    <!-- Schema.org JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebApplication",
      "name": "TrackMyEvents",
      "applicationCategory": "ProductivityApplication",
      "operatingSystem": "All",
      "description": "An intuitive platform to log, view, countdown and manage important life and work moments."
    }
    </script>

    <!-- Dark Mode Init -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-950 selection:bg-indigo-500 selection:text-white transition-colors duration-200">

    <!-- Navigation Bar -->
    <nav class="sticky top-0 z-50 backdrop-blur-md bg-white/80 dark:bg-gray-900/80 border-b border-gray-200/50 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <img class="h-9 w-auto rounded-lg shadow-xs" src="{{ url('/logo_tme.png') }}" alt="TrackMyEvents">
                    <span class="text-xl font-black tracking-tight text-gray-900 dark:text-white">TrackMy<span class="text-indigo-600 dark:text-indigo-400">Events</span></span>
                </a>

                <!-- Nav Links & Auth -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <button type="button" onclick="window.toggleDarkMode()" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition" title="Toggle Theme">
                        <svg class="h-4 w-4 hidden dark:block text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <svg class="h-4 w-4 block dark:hidden text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md transition">
                            Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md transition">
                            Get Started Free
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Interactive Demo Countdown Widget -->
    <section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800 text-indigo-700 dark:text-indigo-300 text-xs font-bold mb-6">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-ping"></span>
                The Modern Countdown & Milestone Tracker
            </div>

            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-gray-900 dark:text-white max-w-4xl mx-auto leading-tight mb-6">
                Never lose track of your <span class="bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent">biggest moments</span>
            </h1>

            <p class="text-base sm:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Log, visualize, and countdown to vacations, product launches, birthdays, fitness goals, and life memories with live-ticking precision.
            </p>

            <div class="flex flex-wrap justify-center gap-4 mb-16">
                <a href="{{ route('register') }}" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white text-base font-extrabold rounded-2xl shadow-xl hover:shadow-indigo-500/25 transition transform hover:-translate-y-0.5">
                    Start Tracking Free &rarr;
                </a>
                <a href="#live-demo" class="px-8 py-3.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 text-base font-bold rounded-2xl shadow-xs transition">
                    Try Live Demo Below
                </a>
            </div>

            <!-- Live Interactive Demo Widget (Try without login) -->
            <div id="live-demo" class="max-w-3xl mx-auto bg-white dark:bg-gray-900 rounded-3xl shadow-2xl p-6 sm:p-8 border border-gray-200/80 dark:border-gray-800 text-left"
                 x-data="{
                    demoName: 'Trip to Tokyo',
                    demoDate: '{{ date('Y') + 1 }}-07-15',
                    demoTime: '09:00',
                    demoGradient: 'from-purple-600 to-indigo-600',
                    ...countdown('{{ date('Y') + 1 }}-07-15', '09:00:00', false)
                 }">
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
                    <div>
                        <span class="text-xs font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Interactive Demo</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Create a preview countdown instantly</h3>
                    </div>
                    <span class="text-xs px-2.5 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 rounded-full font-bold">Live Ticking</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Event Name</label>
                        <input type="text" x-model="demoName" class="w-full text-xs p-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Date</label>
                        <input type="date" x-model="demoDate" @change="parseTarget(); calculate()" class="w-full text-xs p-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Gradient Theme</label>
                        <select x-model="demoGradient" class="w-full text-xs p-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                            <option value="from-purple-600 to-indigo-600">Purple to Indigo</option>
                            <option value="from-blue-600 to-teal-500">Blue to Teal</option>
                            <option value="from-pink-500 to-rose-500">Pink to Rose</option>
                            <option value="from-amber-500 to-orange-600">Amber to Orange</option>
                        </select>
                    </div>
                </div>

                <!-- Preview Countdown Card -->
                <div class="rounded-2xl p-6 text-white text-center shadow-lg bg-gradient-to-r transition-all duration-300"
                     :class="demoGradient">
                    <span class="px-2.5 py-0.5 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-extrabold uppercase tracking-wider">Preview Card</span>
                    <h4 class="text-2xl font-black mt-2 mb-1" x-text="demoName"></h4>
                    <p class="text-xs text-white/80 mb-4" x-text="demoDate + ' at ' + demoTime"></p>

                    <div class="grid grid-cols-6 gap-2 max-w-md mx-auto text-center font-mono">
                        <div class="bg-black/25 backdrop-blur-md p-2 rounded-lg">
                            <span class="block text-xl font-black" x-text="years"></span>
                            <span class="text-[9px] uppercase font-sans text-white/70">Y</span>
                        </div>
                        <div class="bg-black/25 backdrop-blur-md p-2 rounded-lg">
                            <span class="block text-xl font-black" x-text="months"></span>
                            <span class="text-[9px] uppercase font-sans text-white/70">M</span>
                        </div>
                        <div class="bg-black/25 backdrop-blur-md p-2 rounded-lg">
                            <span class="block text-xl font-black" x-text="days"></span>
                            <span class="text-[9px] uppercase font-sans text-white/70">D</span>
                        </div>
                        <div class="bg-black/25 backdrop-blur-md p-2 rounded-lg">
                            <span class="block text-xl font-black" x-text="String(hours).padStart(2,'0')"></span>
                            <span class="text-[9px] uppercase font-sans text-white/70">H</span>
                        </div>
                        <div class="bg-black/25 backdrop-blur-md p-2 rounded-lg">
                            <span class="block text-xl font-black" x-text="String(minutes).padStart(2,'0')"></span>
                            <span class="text-[9px] uppercase font-sans text-white/70">M</span>
                        </div>
                        <div class="bg-black/25 backdrop-blur-md p-2 rounded-lg">
                            <span class="block text-xl font-black text-rose-300 animate-pulse" x-text="String(seconds).padStart(2,'0')"></span>
                            <span class="text-[9px] uppercase font-sans text-white/70">S</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('register') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                        Save this event to your account &rarr;
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- Features Showcase Grid -->
    <section class="py-20 bg-gray-100/70 dark:bg-gray-900/50 border-t border-b border-gray-200/60 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-xs font-extrabold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 mb-2">Key Features</h2>
                <h3 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white">Designed for life, work, and every goal in between</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-200/60 dark:border-gray-700">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Live Real-Time Precision</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Count down years, months, days, workdays, hours, minutes, and seconds ticking smoothly in real-time.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-200/60 dark:border-gray-700">
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Recurring Schedules</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Automate yearly birthdays, anniversaries, monthly reviews, and weekly milestone repetitions effortlessly.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-200/60 dark:border-gray-700">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">iCal & Google Calendar</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Export `.ics` files for Apple Calendar & Outlook, or add events directly to Google Calendar with one click.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-200/60 dark:border-gray-700">
                    <div class="w-12 h-12 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Milestone Checklists</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Break large goals into step-by-step tasks with live completion progress bars and check-offs.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-200/60 dark:border-gray-700">
                    <div class="w-12 h-12 bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Shareable Public Links</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Share live countdown pages with friends, family, and colleagues with OpenGraph rich previews.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-200/60 dark:border-gray-700">
                    <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">CSV & JSON Portability</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Bulk import and export your data anytime. Keep full ownership of your milestones with no lock-in.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials / Social Proof -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mb-12">Loved by planners, travelers, and event organizers</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">"TrackMyEvents made planning our 2-week vacation across Japan effortless. The ticking seconds countdown kept our group so hyped!"</p>
                    <div class="font-bold text-xs text-gray-900 dark:text-white">Sarah M. &bull; <span class="font-normal text-gray-500">Travel Enthusiast</span></div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">"The recurring birthday and anniversary feature ensures I never miss an important milestone again. The UI is gorgeous."</p>
                    <div class="font-bold text-xs text-gray-900 dark:text-white">David K. &bull; <span class="font-normal text-gray-500">Product Manager</span></div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">"The iCal and Google Calendar sync is super smooth. Plus being able to export our entire timeline to JSON is huge."</p>
                    <div class="font-bold text-xs text-gray-900 dark:text-white">Elena R. &bull; <span class="font-normal text-gray-500">Event Coordinator</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Banner -->
    <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-3xl sm:text-4xl font-black mb-4 tracking-tight">Ready to start tracking your moments?</h3>
            <p class="text-indigo-100 text-base mb-8 max-w-xl mx-auto">Join thousands of users organizing milestones, countdowns, and life events.</p>
            <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-indigo-600 hover:bg-gray-100 font-extrabold rounded-2xl shadow-2xl text-base transition transform hover:-translate-y-0.5 inline-block">
                Create Free Account
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-12 text-sm text-gray-500 dark:text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <img class="h-6 w-auto rounded" src="{{ url('/logo_tme.png') }}" alt="TrackMyEvents">
                <span class="font-bold text-gray-900 dark:text-white">TrackMyEvents</span>
            </div>
            <div class="flex flex-wrap items-center gap-6 text-xs font-semibold">
                <a href="{{ route('about') }}" class="hover:text-indigo-600 transition">About & Roadmap</a>
                <a href="{{ route('privacy') }}" class="hover:text-indigo-600 transition">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="hover:text-indigo-600 transition">Terms of Service</a>
                <a href="{{ route('login') }}" class="hover:text-indigo-600 transition">Sign In</a>
            </div>

            <!-- Pexels Attribution & Official Logos -->
            <div class="flex items-center gap-3">
                <a href="https://www.pexels.com" target="_blank" rel="noopener" class="text-xs text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium">
                    Photos provided by Pexels
                </a>
                <a href="https://www.pexels.com" target="_blank" rel="noopener">
                    <img src="https://images.pexels.com/lib/api/pexels.png" class="h-6 dark:hidden" alt="Pexels Logo" />
                    <img src="https://images.pexels.com/lib/api/pexels-white.png" class="h-6 hidden dark:block" alt="Pexels Logo" />
                </a>
            </div>

            <p class="text-xs">&copy; {{ date('Y') }} TrackMyEvents. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
