<x-app-layout>
  <div class="py-6 sm:py-10" x-data="batchManager">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      @if(count($events) < 1) 
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 sm:p-12 text-center shadow-md border border-gray-100 dark:border-gray-700/60 max-w-lg mx-auto my-6">
          <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
          </div>
          <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2">No events found</h3>
          <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-6">Create your first milestone or countdown to start tracking important dates.</p>
          <a href="{{ route('event.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl shadow-md transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Event
          </a>
        </div>
      @else

        <!-- Header & Filter / View Switcher Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
          <div>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
              {{ $currentTag ? '#' . $currentTag . ' Events' : ($currentFilter ? $currentFilter . ' Events' : 'All Events') }}
              @if($currentSearch)
                <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 block sm:inline mt-0.5">&bull; search: "{{ $currentSearch }}"</span>
              @endif
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Showing {{ $events->total() }} total tracking items</p>
          </div>

          <!-- Tags Filter Chips & View Switcher -->
          <div class="flex flex-wrap items-center justify-between gap-3 w-full md:w-auto">
            
            <!-- Dynamic Tags Filter Chips (Horizontal Scroll) -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 max-w-full sm:max-w-none">
              <a href="{{ route('dashboard') }}"
                 class="px-3 py-1.5 text-xs font-bold rounded-xl whitespace-nowrap transition {{ !request('tag') && !request('filter') ? 'bg-indigo-600 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                All
              </a>
              @foreach(($availableTags ?? ['Birthday', 'Personal', 'Work', 'Travel', 'Reminder']) as $tag)
                <a href="{{ route('dashboard', ['tag' => $tag]) }}"
                   class="px-3 py-1.5 text-xs font-bold rounded-xl whitespace-nowrap transition {{ request('tag') === $tag ? 'bg-indigo-600 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                  #{{ $tag }}
                </a>
              @endforeach
            </div>

            <!-- View Switcher -->
            <div class="inline-flex bg-gray-200/70 dark:bg-gray-800 p-1 rounded-2xl shadow-xs border border-gray-300/40 dark:border-gray-700" role="group">
              <a href="{{ request()->fullUrlWithQuery(['view' => 'cards']) }}"
                class="px-3.5 py-1.5 text-xs font-extrabold rounded-xl transition {{ ($viewMode ?? 'cards') === 'cards' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-xs' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900' }}">
                Grid
              </a>
              <a href="{{ request()->fullUrlWithQuery(['view' => 'timeline']) }}"
                class="px-3.5 py-1.5 text-xs font-extrabold rounded-xl transition {{ ($viewMode ?? 'cards') === 'timeline' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-xs' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900' }}">
                Timeline
              </a>
              <a href="{{ request()->fullUrlWithQuery(['view' => 'calendar']) }}"
                class="px-3.5 py-1.5 text-xs font-extrabold rounded-xl transition {{ ($viewMode ?? 'cards') === 'calendar' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-xs' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900' }}">
                Calendar
              </a>
            </div>

          </div>
        </div>

        <!-- Batch Selection Toolbar (visible when cards are selected) -->
        <div x-show="selectedIds.length > 0" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="mb-6 p-4 bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-200 dark:border-indigo-800 rounded-3xl flex flex-wrap items-center justify-between gap-3 shadow-md"
             x-cloak>
          <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-indigo-600 text-white rounded-xl text-xs font-black" x-text="selectedIds.length + ' selected'"></span>
            <button type="button" @click="clearSelection()" class="text-xs text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-bold">Deselect all</button>
          </div>

          <div class="flex flex-wrap items-center gap-2">
            <!-- Change Category -->
            <div class="flex items-center gap-1.5" x-data="{ cat: 'Personal' }">
              <select x-model="cat" class="text-xs py-1.5 pl-2.5 pr-6 rounded-xl bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-200">
                <option value="Personal">Personal</option>
                <option value="Birthday">Birthday</option>
                <option value="Work">Work</option>
                <option value="Reminder">Reminder</option>
                <option value="Private">Private</option>
              </select>
              <button type="button" @click="submitBulkCategory('bulk-category-form', cat)" class="px-3 py-1.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold shadow-xs">
                Set Category
              </button>
            </div>

            <!-- Export Selected -->
            <button type="button" @click="document.getElementById('bulk-export-ids').value = JSON.stringify(selectedIds); document.getElementById('bulk-export-format').value = 'csv'; document.getElementById('bulk-export-form').submit();"
                    class="px-3 py-1.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold shadow-xs">
              CSV
            </button>

            <button type="button" @click="document.getElementById('bulk-export-ids').value = JSON.stringify(selectedIds); document.getElementById('bulk-export-format').value = 'json'; document.getElementById('bulk-export-form').submit();"
                    class="px-3 py-1.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold shadow-xs">
              JSON
            </button>

            <!-- Bulk Delete -->
            <button type="button" @click="submitBulkDelete('bulk-delete-form')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold shadow-xs">
              Delete
            </button>
          </div>
        </div>

        <!-- Hidden Forms for Bulk Actions -->
        <form id="bulk-delete-form" action="{{ route('event.bulk_delete') }}" method="POST" class="hidden">
          @csrf
          <input type="hidden" name="ids" id="bulk-delete-ids">
        </form>
        <form id="bulk-category-form" action="{{ route('event.bulk_category') }}" method="POST" class="hidden">
          @csrf
          <input type="hidden" name="ids" id="bulk-category-ids">
          <input type="hidden" name="category" id="bulk-category-type">
        </form>
        <form id="bulk-export-form" action="{{ route('event.bulk_export') }}" method="POST" class="hidden">
          @csrf
          <input type="hidden" name="ids" id="bulk-export-ids">
          <input type="hidden" name="format" id="bulk-export-format" value="csv">
        </form>

        @if(($viewMode ?? 'cards') === 'timeline')
          {{-- Timeline Mode --}}
          <div class="relative border-l-2 border-indigo-500 dark:border-indigo-400 ml-4 md:ml-8 my-6 space-y-6 text-left">
            @foreach($events as $event)
            @php
              $isPrivateEvent = ($event->event_type === 'Private') || (is_array($event->tags) && in_array('private', array_map('strtolower', $event->tags)));
            @endphp
            <div class="relative pl-6 md:pl-8">
              <div class="absolute -left-2.5 top-2 w-5 h-5 rounded-full border-4 border-white dark:border-gray-900 bg-indigo-600"></div>
              <div class="bg-white dark:bg-gray-800/80 p-5 sm:p-6 rounded-3xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 transition relative overflow-hidden">
                
                @if($isPrivateEvent)
                  <div x-show="!$store.privateLock.isUnlocked" @click="$store.privateLock.openModal()" class="absolute inset-0 z-20 bg-gray-950/70 backdrop-blur-md flex items-center justify-between p-6 cursor-pointer text-white">
                    <div class="flex items-center gap-3">
                      <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                      <span class="text-xs font-black uppercase tracking-wider">Private Event (Locked)</span>
                    </div>
                    <span class="text-xs text-indigo-300 underline font-bold">Enter PIN &rarr;</span>
                  </div>
                @endif

                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-2" :class="{ 'filter blur-sm': {{ $isPrivateEvent ? 'true' : 'false' }} && !$store.privateLock.isUnlocked }">
                  <div class="flex flex-wrap items-center gap-2">
                    <input type="checkbox" :checked="selectedIds.includes({{ $event->id }})" @click="toggleSelect({{ $event->id }})" class="w-4 h-4 text-indigo-600 rounded border-gray-300">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $event->isPastEvent() ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' }}">
                      {{ $event->isPastEvent() ? 'Past' : 'Upcoming' }} ({{ $event->event_type }})
                    </span>
                    @if(is_array($event->tags))
                      @foreach($event->tags as $t)
                        <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-[10px] font-bold rounded-full">#{{ $t }}</span>
                      @endforeach
                    @endif
                    @if($event->is_recurring && $event->recurrence_type !== 'none')
                      <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-[10px] font-bold rounded-full">
                        {{ ucfirst($event->recurrence_type) }}
                      </span>
                    @endif
                  </div>
                  <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $event->event_date }} {{ $event->event_time ?? '' }}</span>
                </div>
                
                <a href="{{ route('event.show', $event->id) }}" class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition" :class="{ 'filter blur-sm': {{ $isPrivateEvent ? 'true' : 'false' }} && !$store.privateLock.isUnlocked }">
                  {{ $event->event_name }}
                </a>

                <div x-data="countdown('{{ $event->getTargetDateTime()->format('Y-m-d') }}', '{{ $event->getTargetDateTime()->format('H:i:s') }}', {{ $event->isPastEvent() ? 'true' : 'false' }})" class="mt-2 text-indigo-600 dark:text-indigo-400 font-black font-mono text-base sm:text-lg" :class="{ 'filter blur-sm': {{ $isPrivateEvent ? 'true' : 'false' }} && !$store.privateLock.isUnlocked }">
                  <span x-text="years + 'Y ' + months + 'M ' + days + 'D ' + String(hours).padStart(2,'0') + ':' + String(minutes).padStart(2,'0') + ':' + String(seconds).padStart(2,'0')"></span>
                  <span class="text-xs text-gray-500 font-normal font-sans" x-text="isPast ? '(since)' : '(remaining)'"></span>
                </div>
              </div>
            </div>
            @endforeach
          </div>

        @elseif(($viewMode ?? 'cards') === 'calendar')
          {{-- Calendar Mode --}}
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 text-left">
            @php
              $groupedEvents = $events->groupBy(function($item) {
                  return \Carbon\Carbon::parse($item->event_date)->format('F Y');
              });
            @endphp

            @forelse($groupedEvents as $monthYear => $monthEvents)
              <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-3xl border border-gray-200/70 dark:border-gray-700 shadow-xs">
                <h4 class="font-black text-sm text-indigo-600 dark:text-indigo-400 border-b border-gray-100 dark:border-gray-700 pb-2 mb-3">
                  {{ $monthYear }}
                </h4>
                <div class="space-y-2">
                  @foreach($monthEvents as $event)
                    @php
                      $isPrivate = ($event->event_type === 'Private') || (is_array($event->tags) && in_array('private', array_map('strtolower', $event->tags)));
                    @endphp
                    <a href="{{ route('event.show', $event->id) }}" 
                       @if($isPrivate) @click.prevent="if(!$store.privateLock.isUnlocked) { $store.privateLock.openModal(); } else { window.location.href = '{{ route('event.show', $event->id) }}'; }" @endif
                       class="block p-3 bg-gray-50 dark:bg-gray-700/50 rounded-2xl hover:border-indigo-500 border border-transparent transition relative overflow-hidden">
                      
                      @if($isPrivate)
                        <div x-show="!$store.privateLock.isUnlocked" class="absolute inset-0 bg-gray-900/80 backdrop-blur-xs flex items-center justify-center text-[11px] font-bold text-indigo-300 gap-1.5">
                          <span>🔒 Private Event</span>
                        </div>
                      @endif

                      <div class="flex justify-between items-start" :class="{ 'filter blur-xs': {{ $isPrivate ? 'true' : 'false' }} && !$store.privateLock.isUnlocked }">
                        <span class="font-bold text-xs sm:text-sm text-gray-900 dark:text-white truncate">{{ $event->event_name }}</span>
                        <span class="text-[10px] font-black px-2 py-0.5 bg-white dark:bg-gray-800 text-indigo-600 rounded-lg shadow-xs">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                      </div>
                      <p class="text-[10px] text-gray-500 mt-1" :class="{ 'filter blur-xs': {{ $isPrivate ? 'true' : 'false' }} && !$store.privateLock.isUnlocked }">
                        {{ $event->event_type }}
                        @if(is_array($event->tags) && count($event->tags) > 0)
                          &bull; #{{ $event->tags[0] }}
                        @endif
                      </p>
                    </a>
                  @endforeach
                </div>
              </div>
            @empty
              <div class="col-span-full py-8 text-center text-gray-500">No events found.</div>
            @endforelse
          </div>

        @else
          {{-- Cards Grid View --}}
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($events as $event)
              @php
                $isPrivateEvent = ($event->event_type === 'Private') || (is_array($event->tags) && in_array('private', array_map('strtolower', $event->tags)));
                $selectedFields = $event->getSelectedFieldsArray();
              @endphp
              <div class="relative group rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-300 h-80 flex flex-col justify-between border border-gray-200/50 dark:border-gray-700/60 transform hover:-translate-y-1.5"
                   :class="cardMenuOpen ? 'z-30' : 'z-10'"
                   x-data="{ cardMenuOpen: false }">
                
                <!-- Background Layer (Clipped to rounded card) -->
                <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none">
                  @if($event->is_background_image)
                    <img src="{{ asset('uploads/backgrounds/'.$event->background) }}" alt="{{ $event->event_name }}" 
                         :class="{ 'filter blur-xl scale-110': {{ $isPrivateEvent ? 'true' : 'false' }} && !$store.privateLock.isUnlocked }"
                         class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950/90 via-black/40 to-black/30"></div>
                  @else
                    <div :class="{ 'filter blur-md': {{ $isPrivateEvent ? 'true' : 'false' }} && !$store.privateLock.isUnlocked }" class="w-full h-full bg-gradient-to-br {{ $event->getGradientClasses() }} transition-all"></div>
                    <div class="absolute inset-0 bg-black/15"></div>
                  @endif

                  <!-- Blur Lock Overlay for Private Event when locked -->
                  @if($isPrivateEvent)
                    <div x-show="!$store.privateLock.isUnlocked" class="absolute inset-0 bg-gray-950/70 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center transition">
                      <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-3 text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                      </div>
                    </div>
                  @endif
                </div>

                <!-- Private Lock Click Interceptor -->
                @if($isPrivateEvent)
                  <div x-show="!$store.privateLock.isUnlocked" @click.stop="$store.privateLock.openModal()" class="absolute inset-0 z-20 rounded-3xl cursor-pointer"></div>
                @endif

                <!-- Card Top: Category/Tags Badge, Recurring Badge & Quick Menu -->
                <div class="relative z-20 p-4 sm:p-5 flex items-center justify-between"
                     :class="{ 'opacity-20 pointer-events-none': {{ $isPrivateEvent ? 'true' : 'false' }} && !$store.privateLock.isUnlocked }">
                  <div class="flex flex-wrap items-center gap-1.5">
                    <input type="checkbox" :checked="selectedIds.includes({{ $event->id }})" @click.stop="toggleSelect({{ $event->id }})" class="w-4 h-4 text-indigo-600 bg-white/40 border-white/50 rounded-md focus:ring-0 cursor-pointer">
                    <span class="px-2.5 py-0.5 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-wider text-white">
                      {{ $event->event_type }}
                    </span>
                    @if(is_array($event->tags) && count($event->tags) > 0)
                      <span class="px-2 py-0.5 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-bold text-white/90">
                        #{{ $event->tags[0] }}
                      </span>
                    @endif
                    @if($event->is_recurring && $event->recurrence_type !== 'none')
                      <span class="px-2 py-0.5 bg-black/30 backdrop-blur-md rounded-full text-[10px] font-bold text-indigo-200 uppercase">
                        {{ ucfirst($event->recurrence_type) }}
                      </span>
                    @endif
                  </div>

                  <!-- Quick Action Menu Dropdown -->
                  <div class="relative">
                    <button type="button" @click.stop="cardMenuOpen = !cardMenuOpen" class="w-8 h-8 rounded-full bg-black/30 backdrop-blur-md flex items-center justify-center text-white hover:bg-black/50 transition">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                    </button>

                    <div x-show="cardMenuOpen" @click.away="cardMenuOpen = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl py-1.5 text-xs border border-gray-100 dark:border-gray-700 z-50">
                      <a href="{{ route('event.show', $event->id) }}" class="block px-4 py-2 font-semibold text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700">View Full Details</a>
                      <a href="{{ route('event.edit', $event->id) }}" class="block px-4 py-2 font-semibold text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700">Edit Event</a>
                      <a href="{{ route('event.ical', $event->id) }}" class="block px-4 py-2 font-semibold text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700">Export iCal (.ics)</a>
                      <a href="{{ $event->google_calendar_url }}" target="_blank" class="block px-4 py-2 font-semibold text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700">Add to Google Cal</a>
                      <a href="{{ route('event.public_share', $event->share_token ?? '') }}" target="_blank" class="block px-4 py-2 font-semibold text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700">Share Public Link</a>
                      <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                      <button type="button" @click="$dispatch('open-delete-modal', { actionUrl: '{{ route('event.delete', $event->id) }}', title: 'Delete {{ addslashes($event->event_name) }}?' }); cardMenuOpen = false;" class="block w-full text-left px-4 py-2 font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                        Delete Event
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Card Center & Bottom: Clickable Link -->
                <a href="{{ route('event.show', $event->id) }}" class="relative z-10 p-5 flex flex-col justify-end text-white text-center flex-1"
                   :class="{ 'opacity-0 pointer-events-none': {{ $isPrivateEvent ? 'true' : 'false' }} && !$store.privateLock.isUnlocked }">
                  <div>
                    <h3 class="text-xl sm:text-2xl font-black mb-1 line-clamp-1 tracking-tight drop-shadow-sm">{{ $event->event_name }}</h3>
                    <p class="text-xs text-white/80 font-medium mb-3">{{ $event->event_date }} {{ $event->event_time ? '&bull; ' . date('h:i A', strtotime($event->event_time)) : '' }}</p>
                  </div>

                  <!-- Live Real-Time Ticking Countdown on Card -->
                  <div x-data="countdown('{{ $event->getTargetDateTime()->format('Y-m-d') }}', '{{ $event->getTargetDateTime()->format('H:i:s') }}', {{ $event->isPastEvent() ? 'true' : 'false' }})" class="bg-black/30 backdrop-blur-md rounded-2xl p-3 shadow-inner">
                    <div class="flex items-baseline justify-center flex-wrap gap-1.5 font-mono text-xl sm:text-2xl font-black tracking-tight">
                      @if(in_array('years', $selectedFields))
                        <span><span x-text="years"></span><span class="text-xs font-sans text-white/70">Y</span></span>
                      @endif
                      @if(in_array('months', $selectedFields))
                        <span><span x-text="months"></span><span class="text-xs font-sans text-white/70">M</span></span>
                      @endif
                      @if(in_array('weeks', $selectedFields) && !in_array('days', $selectedFields))
                        <span><span x-text="weeks"></span><span class="text-xs font-sans text-white/70">W</span></span>
                      @endif
                      @if(in_array('days', $selectedFields))
                        <span><span x-text="days"></span><span class="text-xs font-sans text-white/70">D</span></span>
                      @endif
                      @if(in_array('hours', $selectedFields) || in_array('minutes', $selectedFields) || in_array('seconds', $selectedFields))
                        <span class="text-base sm:text-lg text-white/90">
                          @if(in_array('hours', $selectedFields))<span x-text="String(hours).padStart(2,'0')"></span>@endif<!--
                          -->@if(in_array('hours', $selectedFields) && in_array('minutes', $selectedFields)):@endif<!--
                          -->@if(in_array('minutes', $selectedFields))<span x-text="String(minutes).padStart(2,'0')"></span>@endif<!--
                          -->@if(in_array('minutes', $selectedFields) && in_array('seconds', $selectedFields)):@endif<!--
                          -->@if(in_array('seconds', $selectedFields))<span x-text="String(seconds).padStart(2,'0')"></span>@endif
                        </span>
                      @endif
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-widest mt-0.5" :class="isPast ? 'text-amber-300' : 'text-emerald-300'" x-text="isPast ? '&LeftAngleBracket; Since &RightAngleBracket;' : '&LeftAngleBracket; Remaining &RightAngleBracket;'"></p>
                  </div>
                </a>

              </div>
            @endforeach
          </div>
        @endif

        @if(method_exists($events, 'links'))
          <div class="mt-8">
            {{ $events->links() }}
          </div>
        @endif

      @endif

    </div>
  </div>
</x-app-layout>
