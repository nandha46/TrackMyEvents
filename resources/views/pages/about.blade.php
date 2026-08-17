<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About & Product Roadmap — TrackMyEvents</title>
    <!-- Dark Mode Init -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-950">
    <nav class="border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img class="h-8 w-auto rounded" src="{{ url('/logo_tme.png') }}" alt="TrackMyEvents">
                <span class="font-black text-lg text-gray-900 dark:text-white">TrackMy<span class="text-indigo-600 dark:text-indigo-400">Events</span></span>
            </a>
            <a href="{{ url('/') }}" class="text-xs font-bold text-gray-600 dark:text-gray-300 hover:text-indigo-600">&larr; Back to Home</a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 sm:p-12 shadow-sm border border-gray-200/80 dark:border-gray-800 mb-10">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">About TrackMyEvents</h1>
            <p class="text-sm text-gray-500 mb-8">Crafted for anyone who cherishes moments, tracks milestones, and plans the future.</p>

            <div class="space-y-4 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                <p>
                    TrackMyEvents was born out of a simple desire: a clean, aesthetic, and versatile tool to track both past memories and upcoming milestones with live precision. Whether counting down the days to a much-needed holiday, anticipating a wedding, or reflecting on years since a major life milestone, TrackMyEvents gives you a tailored canvas for your journey.
                </p>
            </div>
        </div>

        <!-- Product Roadmap -->
        <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 sm:p-12 shadow-sm border border-gray-200/80 dark:border-gray-800">
            <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-6">Product Roadmap</h2>

            <div class="space-y-6 border-l-2 border-indigo-500 ml-3 pl-6">
                <div>
                    <span class="px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 text-xs font-bold rounded-full">Released</span>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mt-1">Live Ticking Countdowns & Dynamic Gradients</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Real-time second-by-second countdowns, customizable gradients, image dropzones, and dark mode.</p>
                </div>

                <div>
                    <span class="px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 text-xs font-bold rounded-full">Released</span>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mt-1">iCal Sync, Public Links & Recurring Schedules</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Google Calendar sync, .ics exports, public shareable countdown links, and yearly/monthly recurrence.</p>
                </div>

                <div>
                    <span class="px-2.5 py-0.5 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-full">In Progress</span>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mt-1">Mobile Native Widgets & Push Notifications</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Home screen widgets for iOS and Android, plus web push notification support.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
