<x-app-layout>
  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        @if($event->is_background_image)
          <div class="relative w-full h-80 bg-gray-900">
            <img src="{{ asset('uploads/backgrounds/'.$event->background) }}" alt="{{ $event->event_name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-end p-8 text-white">
              <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider w-max mb-3">{{ $event->event_type }}</span>
              <h1 class="text-3xl font-bold mb-1">{{ $event->event_name }}</h1>
              <p class="text-sm opacity-90">{{ $event->event_date }} {{ $event->event_time ?? '' }}</p>
            </div>
          </div>
        @else
          <div class="w-full h-64 bg-gradient-to-r {{ $event->getGradientClasses() }} flex flex-col justify-end p-8 text-white">
            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider w-max mb-3">{{ $event->event_type }}</span>
            <h1 class="text-3xl font-bold mb-1">{{ $event->event_name }}</h1>
            <p class="text-sm opacity-90">{{ $event->event_date }} {{ $event->event_time ?? '' }}</p>
          </div>
        @endif

        <div class="p-8">
          <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 mb-8 text-center">
            <p class="text-sm uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold mb-2">
              {{ $event->isPastEvent() ? 'Time Elapsed Since Event' : 'Countdown to Event' }}
            </p>
            <p class="text-4xl sm:text-5xl font-extrabold text-indigo-600 dark:text-indigo-400 custom-font-style">
              {{ $event->getYears() }}<span class="text-xl font-medium text-gray-500">Y</span>
              {{ $event->getMonths() }}<span class="text-xl font-medium text-gray-500">M</span>
              {{ $event->getDays() }}<span class="text-xl font-medium text-gray-500">D</span>
            </p>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg text-center">
              <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">Event Type</span>
              <p class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-1">{{ $event->event_type }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg text-center">
              <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">Status</span>
              <p class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-1">{{ $event->isPastEvent() ? 'Past' : 'Upcoming' }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg text-center">
              <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">Date</span>
              <p class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-1">{{ $event->event_date }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg text-center">
              <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">Time</span>
              <p class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-1">{{ $event->event_time ? date('h:i A', strtotime($event->event_time)) : 'All Day' }}</p>
            </div>
          </div>

          <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md font-medium text-sm">
              &larr; Back to Events
            </a>
            <div class="flex gap-3">
              <a href="{{ route('event.edit', $event->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium text-sm">
                Edit Event
              </a>
              <form action="{{ route('event.delete', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md font-medium text-sm">
                  Delete
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
