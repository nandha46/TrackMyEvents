<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Session flash feedback for toast notifications -->
        @if(session('success')) <meta name="flash-success" content="{{ session('success') }}"> @endif
        @if(session('error')) <meta name="flash-error" content="{{ session('error') }}"> @endif
        @if(session('status')) <meta name="flash-status" content="{{ session('status') }}"> @endif

        <title>{{ config('app.name', 'TrackMyEvents') }}</title>

        <!-- Dark Mode Init to prevent flash -->
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
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col justify-between">
        <div>
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200/80 dark:border-gray-800">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Global Toast Container -->
        <div x-data="toastSystem" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none max-w-sm w-full">
            <template x-for="toast in toasts" :key="toast.id">
                <div class="pointer-events-auto flex items-center justify-between p-4 rounded-2xl shadow-xl text-xs font-bold text-white transition-all transform duration-300"
                     :class="{
                         'bg-emerald-600 dark:bg-emerald-500': toast.type === 'success',
                         'bg-rose-600 dark:bg-rose-500': toast.type === 'error',
                         'bg-indigo-600 dark:bg-indigo-500': toast.type === 'info'
                     }">
                    <span x-text="toast.message"></span>
                    <button type="button" @click="removeToast(toast.id)" class="ml-3 text-white/80 hover:text-white">&times;</button>
                </div>
            </template>
        </div>

        <!-- Global Confirmation Delete Modal -->
        <div x-data="confirmModal" 
             @open-delete-modal.window="showModal($event.detail.actionUrl, $event.detail.title, $event.detail.message)"
             x-show="open" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div @click.away="closeModal()" 
                 x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white dark:bg-gray-900 rounded-3xl p-6 max-w-md w-full shadow-2xl border border-gray-200/80 dark:border-gray-800 text-center">
                <div class="w-12 h-12 bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white mb-1" x-text="title"></h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6" x-text="message"></p>
                <div class="flex gap-3">
                    <button type="button" @click="closeModal()" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold transition">
                        Cancel
                    </button>
                    <form :action="formAction" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold shadow-md transition">
                            Confirm Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Global Keyboard Shortcuts Modal -->
        <div x-data="{ open: false }" 
             @toggle-shortcuts-help.window="open = !open" 
             @close-modals.window="open = false" 
             x-show="open" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div @click.away="open = false" class="bg-white dark:bg-gray-900 rounded-3xl p-6 max-w-sm w-full shadow-2xl border border-gray-200 dark:border-gray-800">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-extrabold text-sm text-gray-900 dark:text-white">Keyboard Shortcuts</h3>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">&times;</button>
                </div>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center py-1 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-gray-600 dark:text-gray-400">Create new event</span>
                        <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded font-mono font-bold">N</kbd>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-gray-600 dark:text-gray-400">Focus search bar</span>
                        <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded font-mono font-bold">/</kbd>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-gray-600 dark:text-gray-400">Close open modal / blur</span>
                        <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded font-mono font-bold">Esc</kbd>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-gray-600 dark:text-gray-400">Open shortcuts cheat sheet</span>
                        <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded font-mono font-bold">?</kbd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Global 4-Digit PIN Unlock Modal for Private Events -->
        <div x-data="privateLockModal" 
             x-show="$store.privateLock.modalOpen" 
             @open-pin-modal.window="$store.privateLock.openModal()" 
             @close-modals.window="$store.privateLock.closeModal()" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-md">
            <div @click.away="$store.privateLock.closeModal()" 
                 x-show="$store.privateLock.modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl border border-gray-200/80 dark:border-gray-800 text-center">
                
                <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>

                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-1">Unlock Private Events</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">Enter your 4-digit PIN to unblur private events for 5 minutes.</p>

                <form @submit.prevent="submitPin()" class="space-y-4">
                    <div>
                        <input type="password" maxlength="4" pattern="[0-9]*" inputmode="numeric" 
                               x-ref="pinInputField"
                               x-model="pinInput" 
                               placeholder="••••" 
                               class="w-36 mx-auto text-center tracking-[0.6em] font-mono text-2xl font-black px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div x-show="errorMessage" x-text="errorMessage" class="text-xs text-rose-500 font-bold"></div>

                    <div class="flex gap-2.5 pt-2">
                        <button type="button" @click="$store.privateLock.closeModal()" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-xs font-bold transition">
                            Cancel
                        </button>
                        <button type="submit" :disabled="loading || pinInput.length !== 4"
                                :class="pinInput.length === 4 ? 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-md' : 'bg-gray-200 dark:bg-gray-800 text-gray-400 cursor-not-allowed'"
                                class="flex-1 py-3 rounded-2xl text-xs font-black transition flex items-center justify-center gap-1.5">
                            <span x-show="!loading">Unlock</span>
                            <span x-show="loading" class="inline-block animate-spin">&bull;</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <footer class="w-full text-center py-6 text-xs text-gray-500 dark:text-gray-400 border-t border-gray-200/50 dark:border-gray-800/80 mt-12 bg-white/50 dark:bg-gray-900/50">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-2">
                <span>&copy; {{ date('Y') }} TrackMyEvents. All rights reserved.</span>
                <div class="flex items-center gap-4">
                    <a href="{{ route('privacy') }}" class="hover:underline">Privacy</a>
                    <a href="{{ route('terms') }}" class="hover:underline">Terms</a>
                    <a href="{{ route('about') }}" class="hover:underline">About & Roadmap</a>
                </div>
            </div>
        </footer>
    </body>
</html>
