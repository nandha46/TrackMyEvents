<x-app-layout>
  <div class="py-12">
    <div class="max-w-dvw mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg text-center">
        @if(count($events) < 1) 
        <x-Icons.new-notes />
        <h3 class="font-semibold">No Events</h3>
        <p class="font-thin text-gray-600 my-2">Get started by creating a new event.</p>
        <div class="mt-3">
        <x-new-event-button href="{{ route('event.create') }}" >
          Add New Event
        </x-new-event-button>
        </div>
          @else
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <!-- View Mode Switcher and Filters Summary -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-700 gap-4">
              <div class="text-left">
                <h3 class="text-lg font-semibold">
                  {{ $currentFilter ? $currentFilter . ' Events' : 'All Events' }}
                  @if($currentSearch)
                    <span class="text-sm font-normal text-gray-500">matching "{{ $currentSearch }}"</span>
                  @endif
                </h3>
              </div>
              <div class="inline-flex rounded-md shadow-xs" role="group">
                <a href="{{ request()->fullUrlWithQuery(['view' => 'cards']) }}"
                  class="px-4 py-2 text-sm font-medium rounded-s-lg border border-gray-300 {{ ($viewMode ?? 'cards') === 'cards' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100' }}">
                  Grid
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'timeline']) }}"
                  class="px-4 py-2 text-sm font-medium border-t border-b border-gray-300 {{ ($viewMode ?? 'cards') === 'timeline' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100' }}">
                  Timeline
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'calendar']) }}"
                  class="px-4 py-2 text-sm font-medium rounded-e-lg border border-gray-300 {{ ($viewMode ?? 'cards') === 'calendar' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100' }}">
                  Calendar
                </a>
              </div>
            </div>

            @if(($viewMode ?? 'cards') === 'timeline')
              {{-- Timeline View --}}
              <div class="relative border-l-2 border-indigo-500 dark:border-indigo-400 ml-4 md:ml-8 my-6 space-y-8 text-left">
                @foreach($events as $event)
                <div class="relative pl-6 md:pl-8">
                  <div class="absolute -left-2.5 top-1.5 w-5 h-5 rounded-full border-4 border-white dark:border-gray-800 bg-indigo-600"></div>
                  <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg shadow-xs hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-2">
                      <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $event->isPastEvent() ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' }} w-max">
                        {{ $event->isPastEvent() ? 'Past' : 'Upcoming' }} ({{ $event->event_type }})
                      </span>
                      <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $event->event_date }} {{ $event->event_time ?? '' }}</span>
                    </div>
                    <a href="{{ route('event.show', $event->id) }}" class="text-xl font-bold text-gray-900 dark:text-white hover:text-indigo-600">
                      {{ $event->event_name }}
                    </a>
                    <p class="mt-2 text-indigo-600 dark:text-indigo-400 font-semibold custom-font-style text-lg">
                      {{ $event->getYears() }}Y {{ $event->getMonths() }}M {{ $event->getDays() }}D
                      <span class="text-xs text-gray-500 font-normal">({{ $event->isPastEvent() ? 'since' : 'remaining' }})</span>
                    </p>
                  </div>
                </div>
                @endforeach
              </div>
              @if(method_exists($events, 'links'))
                {{ $events->links() }}
              @endif

            @elseif(($viewMode ?? 'cards') === 'calendar')
              {{-- Calendar View --}}
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 text-left">
                @php
                  $groupedEvents = $events->groupBy(function($item) {
                      return \Carbon\Carbon::parse($item->event_date)->format('F Y');
                  });
                @endphp

                @forelse($groupedEvents as $monthYear => $monthEvents)
                  <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-600">
                    <h4 class="font-bold text-base text-indigo-600 dark:text-indigo-400 border-b border-gray-200 dark:border-gray-600 pb-2 mb-3">
                      {{ $monthYear }}
                    </h4>
                    <div class="space-y-2.5">
                      @foreach($monthEvents as $event)
                        <a href="{{ route('event.show', $event->id) }}" class="block p-2.5 bg-white dark:bg-gray-800 rounded-lg shadow-xs hover:border-indigo-500 border border-transparent transition">
                          <div class="flex justify-between items-start">
                            <span class="font-semibold text-sm text-gray-900 dark:text-white">{{ $event->event_name }}</span>
                            <span class="text-xs font-bold px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                          </div>
                          <p class="text-xs text-gray-500 mt-1">{{ $event->event_type }} &bull; {{ $event->isPastEvent() ? 'Past' : 'Upcoming' }}</p>
                        </a>
                      @endforeach
                    </div>
                  </div>
                @empty
                  <div class="col-span-full py-8 text-center text-gray-500">No events found for this filter.</div>
                @endforelse
              </div>

            @else
              {{-- Cards Grid View --}}
              <div class="grid 2xl:grid-cols-6 xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 gap-4">
                @foreach($events as $event)
                <a href="{{ route('event.show', $event->id) }}" class="block transform transition duration-200 hover:scale-[1.02] hover:shadow-lg rounded-xl overflow-hidden shadow-sm h-64 relative bg-gray-800">
                  @if($event->is_background_image)
                    <img src="{{ asset('uploads/backgrounds/'.$event->background) }}" alt="{{ $event->event_name }}" class="w-full h-full object-cover"
                      loading="lazy">
                    <div class="absolute inset-0 flex flex-col justify-between items-center text-center text-white bg-black/60 p-4">
                      <span class="px-2.5 py-0.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider">{{ $event->event_type }}</span>
                      <div>
                        <h2 class="text-lg font-bold line-clamp-1 mb-1">{{ $event->event_name }}</h2>
                        <p class="text-xs text-gray-300">{{ $event->event_date }}</p>
                      </div>
                      <div>
                        <p class="text-2xl text-white custom-font-style font-extrabold tracking-tight">
                          {{$event->getYears()}}<span>Y</span> {{$event->getMonths()}}<span>M</span> {{$event->getDays()}}<span>D</span>
                        </p>
                        @if($event->isPastEvent())
                          <p class="text-xs text-amber-300 font-semibold mt-1 uppercase tracking-wider">&LeftAngleBracket;Since&RightAngleBracket;</p>
                        @else
                          <p class="text-xs text-emerald-300 font-semibold mt-1 uppercase tracking-wider">&LeftAngleBracket;Remaining&RightAngleBracket;</p>
                        @endif
                      </div>
                    </div>
                  @else
                    {{-- Gradient background card --}}
                    <div class="w-full h-full bg-gradient-to-r {{ $event->getGradientClasses() }} flex flex-col justify-between items-center text-center text-white p-4">
                      <span class="px-2.5 py-0.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider">{{ $event->event_type }}</span>
                      <div>
                        <h2 class="text-lg font-bold line-clamp-1 mb-1">{{ $event->event_name }}</h2>
                        <p class="text-xs text-white/80">{{ $event->event_date }}</p>
                      </div>
                      <div>
                        <p class="text-2xl text-white custom-font-style font-extrabold tracking-tight">
                          {{$event->getYears()}}<span>Y</span> {{$event->getMonths()}}<span>M</span> {{$event->getDays()}}<span>D</span>
                        </p>
                        @if($event->isPastEvent())
                          <p class="text-xs text-amber-200 font-semibold mt-1 uppercase tracking-wider">&LeftAngleBracket;Since&RightAngleBracket;</p>
                        @else
                          <p class="text-xs text-emerald-200 font-semibold mt-1 uppercase tracking-wider">&LeftAngleBracket;Remaining&RightAngleBracket;</p>
                        @endif
                      </div>
                    </div>
                  @endif
                </a>
                @endforeach
              </div>
              @if(method_exists($events, 'links'))
                {{ $events->links() }}
              @endif
            @endif
          </div>
          @endif
      </div>
    </div>
  </div>
</x-app-layout>