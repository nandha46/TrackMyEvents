<x-app-layout>
    <div class="py-6 md:py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Top Navigation & Action Controls -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </a>
                
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('event.edit', $event->id) }}" class="px-4 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700 rounded-2xl text-xs font-bold shadow-xs transition">
                        Edit Event
                    </a>
                    <a href="{{ route('event.public_share', $event->share_token ?? '') }}" target="_blank" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 rounded-2xl text-xs font-bold hover:bg-indigo-100 transition">
                        Public Share Link
                    </a>
                    <button type="button" @click="$dispatch('open-delete-modal', { actionUrl: '{{ route('event.delete', $event->id) }}', title: 'Delete {{ addslashes($event->event_name) }}?' })"
                            class="px-4 py-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-2xl text-xs font-bold transition">
                        Delete
                    </button>
                </div>
            </div>

            <!-- Immersive Hero Event Card -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl sm:rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-200/80 dark:border-gray-800 mb-8">
                
                <!-- Huge Banner / Gradient Header -->
                @if($event->is_background_image)
                  <div class="relative w-full h-80 sm:h-96 bg-gray-900 overflow-hidden">
                    <img src="{{ asset('uploads/backgrounds/'.$event->background) }}" alt="{{ $event->event_name }}" class="w-full h-full object-cover transform scale-105 filter brightness-90">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/50 to-black/20 flex flex-col justify-end p-6 sm:p-10 md:p-12 text-white">
                      <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-3.5 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-extrabold uppercase tracking-wider">{{ $event->event_type }}</span>
                        @if($event->is_recurring && $event->recurrence_type !== 'none')
                          <span class="px-3 py-1 bg-indigo-500/90 backdrop-blur-md rounded-full text-xs font-bold text-white uppercase">{{ ucfirst($event->recurrence_type) }}</span>
                        @endif
                      </div>
                      <h1 class="text-3xl sm:text-5xl md:text-6xl font-black tracking-tight drop-shadow-md leading-tight">{{ $event->event_name }}</h1>
                      <p class="text-sm sm:text-base text-gray-200 mt-2 font-medium">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('l, F j, Y') }} 
                        @if($event->event_time) &bull; {{ date('h:i A', strtotime($event->event_time)) }} @endif
                      </p>
                    </div>
                  </div>
                @else
                  <div class="w-full h-80 sm:h-96 bg-gradient-to-br {{ $event->getGradientClasses() }} flex flex-col justify-end p-6 sm:p-10 md:p-12 text-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10">
                      <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-3.5 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-extrabold uppercase tracking-wider">{{ $event->event_type }}</span>
                        @if($event->is_recurring && $event->recurrence_type !== 'none')
                          <span class="px-3 py-1 bg-black/30 backdrop-blur-md rounded-full text-xs font-bold text-indigo-200 uppercase">{{ ucfirst($event->recurrence_type) }}</span>
                        @endif
                      </div>
                      <h1 class="text-3xl sm:text-5xl md:text-6xl font-black tracking-tight drop-shadow-md leading-tight">{{ $event->event_name }}</h1>
                      <p class="text-sm sm:text-base text-white/90 mt-2 font-medium">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('l, F j, Y') }} 
                        @if($event->event_time) &bull; {{ date('h:i A', strtotime($event->event_time)) }} @endif
                      </p>
                    </div>
                  </div>
                @endif

                <div class="p-6 sm:p-10 md:p-12 space-y-10">
                    <!-- Giant Live-Ticking Countdown Display -->
                    <div x-data="countdown('{{ $event->getTargetDateTime()->format('Y-m-d') }}', '{{ $event->getTargetDateTime()->format('H:i:s') }}', {{ $event->isPastEvent() ? 'true' : 'false' }})" 
                         class="bg-gray-50 dark:bg-gray-800/60 rounded-3xl p-6 sm:p-10 text-center border border-gray-100 dark:border-gray-700/60 shadow-inner">
                        
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest mb-6"
                             :class="isPast ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300'">
                            <span class="w-2 h-2 rounded-full animate-ping" :class="isPast ? 'bg-amber-500' : 'bg-indigo-500'"></span>
                            <span x-show="!isPast">LIVE COUNTDOWN</span>
                            <span x-show="isPast" x-cloak>TIME ELAPSED SINCE EVENT</span>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 max-w-4xl mx-auto text-center">
                            <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 transform hover:scale-105 transition">
                                <span class="block text-3xl sm:text-5xl md:text-6xl font-black text-indigo-600 dark:text-indigo-400 font-mono tracking-tight" x-text="years"></span>
                                <span class="text-xs sm:text-sm font-extrabold text-gray-500 uppercase mt-1 block">Years</span>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 transform hover:scale-105 transition">
                                <span class="block text-3xl sm:text-5xl md:text-6xl font-black text-indigo-600 dark:text-indigo-400 font-mono tracking-tight" x-text="months"></span>
                                <span class="text-xs sm:text-sm font-extrabold text-gray-500 uppercase mt-1 block">Months</span>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 transform hover:scale-105 transition">
                                <span class="block text-3xl sm:text-5xl md:text-6xl font-black text-indigo-600 dark:text-indigo-400 font-mono tracking-tight" x-text="days"></span>
                                <span class="text-xs sm:text-sm font-extrabold text-gray-500 uppercase mt-1 block">Days</span>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 transform hover:scale-105 transition">
                                <span class="block text-3xl sm:text-5xl md:text-6xl font-black text-gray-900 dark:text-white font-mono tracking-tight" x-text="String(hours).padStart(2, '0')"></span>
                                <span class="text-xs sm:text-sm font-extrabold text-gray-500 uppercase mt-1 block">Hours</span>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 transform hover:scale-105 transition">
                                <span class="block text-3xl sm:text-5xl md:text-6xl font-black text-gray-900 dark:text-white font-mono tracking-tight" x-text="String(minutes).padStart(2, '0')"></span>
                                <span class="text-xs sm:text-sm font-extrabold text-gray-500 uppercase mt-1 block">Minutes</span>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 transform hover:scale-105 transition">
                                <span class="block text-3xl sm:text-5xl md:text-6xl font-black text-rose-500 font-mono tracking-tight animate-pulse" x-text="String(seconds).padStart(2, '0')"></span>
                                <span class="text-xs sm:text-sm font-extrabold text-gray-500 uppercase mt-1 block">Seconds</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description & Notes -->
                    @if($event->description)
                        <div class="bg-gray-50 dark:bg-gray-800/40 rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-gray-800">
                            <h3 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3">Event Notes</h3>
                            <p class="text-base sm:text-lg text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
                        </div>
                    @endif

                    <!-- Milestones & Subtasks Section -->
                    @if(!empty($event->milestones) && count($event->milestones) > 0)
                        <div x-data="{ progress: {{ $event->getMilestonesProgress() }} }" class="bg-gray-50 dark:bg-gray-800/40 rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider">Milestones Checklist</h3>
                                    <p class="text-xs text-gray-500">Track and check off steps for this event</p>
                                </div>
                                <span class="text-sm font-extrabold text-indigo-600 dark:text-indigo-400 font-mono" x-text="progress + '% completed'"></span>
                            </div>

                            <!-- Progress bar -->
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-6 overflow-hidden">
                                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-3 rounded-full transition-all duration-500" :style="'width: ' + progress + '%'"></div>
                            </div>

                            <div class="space-y-3">
                                @foreach($event->milestones as $milestone)
                                    <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 shadow-xs"
                                         x-data="{ done: {{ !empty($milestone['completed']) ? 'true' : 'false' }} }">
                                        <label class="flex items-center gap-3.5 cursor-pointer select-none w-full">
                                            <input type="checkbox" x-model="done" 
                                                   @change="fetch('{{ route('event.milestone_toggle', $event->id) }}', {
                                                       method: 'POST',
                                                       headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                                       body: JSON.stringify({ milestone_id: '{{ $milestone['id'] }}' })
                                                   }).then(r => r.json()).then(d => { progress = d.progress; })"
                                                   class="w-5 h-5 text-indigo-600 rounded-lg border-gray-300 focus:ring-indigo-500">
                                            <span class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-200" :class="done ? 'line-through text-gray-400 dark:text-gray-500' : ''">
                                                {{ $milestone['title'] }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Bottom Action Controls -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                            <a href="{{ route('event.ical', $event->id) }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 py-3 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 rounded-2xl text-xs sm:text-sm font-bold border border-indigo-200 dark:border-indigo-800 hover:bg-indigo-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Export iCal (.ics)
                            </a>
                            <a href="{{ $event->google_calendar_url }}" target="_blank" rel="noopener noreferrer" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-2xl text-xs sm:text-sm font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                Add to Google Calendar
                            </a>
                        </div>

                        <button type="button" onclick="window.print()" class="text-xs sm:text-sm font-bold text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Print / Save PDF
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
