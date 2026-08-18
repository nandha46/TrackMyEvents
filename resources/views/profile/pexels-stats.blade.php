<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Pexels API Rate Limit Statistics') }}
            </h2>
            <a href="{{ route('profile.edit') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                &larr; Back to Profile
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- API Configuration Notice / Setup Help -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700/60 pb-6 mb-6">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white">Pexels Integration Status</h3>
                            @if($isConfigured)
                                <span class="px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-xs font-bold rounded-full">
                                    Connected
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300 text-xs font-bold rounded-full">
                                    Demo Mode (No API Key)
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Pexels API provides royalty-free curated & searchable high-resolution photos for event backgrounds.
                        </p>
                    </div>

                    <a href="https://www.pexels.com/api/" target="_blank" rel="noopener" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-2xl text-xs font-bold text-gray-800 dark:text-gray-200 transition shrink-0">
                        Pexels API Portal &rarr;
                    </a>
                </div>

                <!-- Rate Limit Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <!-- Limit -->
                    <div class="p-5 bg-gray-50 dark:bg-gray-700/40 rounded-2xl border border-gray-100 dark:border-gray-700 text-center">
                        <span class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">X-Ratelimit-Limit</span>
                        <span class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white font-mono">
                            {{ number_format($rateLimits['limit'] ?? 20000) }}
                        </span>
                        <span class="block text-[10px] text-gray-400 mt-1">Total requests allowed / month</span>
                    </div>

                    <!-- Remaining -->
                    <div class="p-5 bg-indigo-50 dark:bg-indigo-950/40 rounded-2xl border border-indigo-100 dark:border-indigo-800 text-center">
                        <span class="block text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-1">X-Ratelimit-Remaining</span>
                        <span class="text-2xl sm:text-3xl font-black text-indigo-600 dark:text-indigo-400 font-mono">
                            {{ number_format($rateLimits['remaining'] ?? 20000) }}
                        </span>
                        <span class="block text-[10px] text-indigo-400 mt-1">Requests remaining</span>
                    </div>

                    <!-- Reset -->
                    <div class="p-5 bg-gray-50 dark:bg-gray-700/40 rounded-2xl border border-gray-100 dark:border-gray-700 text-center">
                        <span class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">X-Ratelimit-Reset</span>
                        <span class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white font-mono block">
                            {{ $rateLimits['reset'] ?? time() }}
                        </span>
                        <span class="block text-[10px] text-gray-400 mt-1">
                            {{ $rateLimits['reset_human'] ?? date('Y-m-d H:i:s', time() + 3600) }}
                        </span>
                    </div>
                </div>

                <!-- Usage Progress Bar -->
                @php
                    $limitVal = (int) ($rateLimits['limit'] ?? 20000);
                    $remainingVal = (int) ($rateLimits['remaining'] ?? 20000);
                    $usedVal = max(0, $limitVal - $remainingVal);
                    $usedPercentVal = $limitVal > 0 ? min(100, max(0, (int) round(($usedVal / $limitVal) * 100))) : 0;
                @endphp
                <div class="mb-6">
                    <div class="flex justify-between text-xs font-bold mb-1.5">
                        <span class="text-gray-700 dark:text-gray-300">API Quota Utilization</span>
                        <span class="text-indigo-600 dark:text-indigo-400">{{ $usedPercentVal }}% used ({{ number_format($usedVal) }} / {{ number_format($limitVal) }})</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $usedPercentVal }} %"></div>
                    </div>
                </div>

                <!-- Raw Response Sample Header Representation -->
                <div class="bg-gray-900 text-gray-200 p-4 rounded-2xl font-mono text-xs overflow-x-auto">
                    <div class="text-gray-400 mb-1"># Pexels Rate Limit Response Headers (Live Snapshot)</div>
                    <div><span class="text-indigo-400">X-Ratelimit-Limit:</span> {{ $rateLimits['limit'] ?? 20000 }}</div>
                    <div><span class="text-indigo-400">X-Ratelimit-Remaining:</span> {{ $rateLimits['remaining'] ?? 20000 }}</div>
                    <div><span class="text-indigo-400">X-Ratelimit-Reset:</span> {{ $rateLimits['reset'] ?? time() }}</div>
                    <div class="text-gray-500 text-[10px] mt-2">Last recorded: {{ $rateLimits['updated_at'] ?? now()->toDateTimeString() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
