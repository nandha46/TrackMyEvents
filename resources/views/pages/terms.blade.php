<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms of Service — TrackMyEvents</title>
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
        <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 sm:p-12 shadow-sm border border-gray-200/80 dark:border-gray-800">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Terms of Service</h1>
            <p class="text-xs text-gray-500 mb-8">Last updated: August 2026</p>

            <div class="space-y-6 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                <section>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">1. Agreement to Terms</h2>
                    <p>By accessing or using TrackMyEvents, you agree to be bound by these Terms of Service. If you disagree with any part of the terms, you may not access the service.</p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">2. User Accounts</h2>
                    <p>When you create an account, you must provide accurate, complete information. You are responsible for safeguarding your login credentials and for any activities or actions under your account.</p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">3. Acceptable Use</h2>
                    <p>You agree not to misuse the service or assist anyone else in doing so. You may not upload harmful content, execute automated scraping, or disrupt our servers.</p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">4. Termination</h2>
                    <p>We may terminate or suspend access to our service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.</p>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
