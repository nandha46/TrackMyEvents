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
            {{-- Background image card --}}
            <div class="grid 2xl:grid-cols-6 xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 gap-4">
              @foreach($events as $event)
              @if($event->is_background_image)
              <div class="relative aspect-w-1 aspect-h-1">
                <div data-loading="true" class="absolute inset-0 bg-gray-200 animate-pulse"></div>
                <img src="{{ asset('uploads/backgrounds/'.$event->background) }}" alt="Image 1" class="w-full h-full object-cover"
                  loading="lazy">
                <div
                  class="absolute inset-0 flex flex-col justify-center items-center text-center text-white bg-black bg-opacity-50">
                  <h2 class="text-xl font-semibold mb-2">{{ $event->event_name }}</h2>
                  <h2 class="text-xl font-semibold mb-2">{{ $event->event_date }}</h2>
                  <p class="text-3xl text-white custom-font-style"> {{$event->getYears()}}<span>Y</span>
                    {{$event->getMonths()}}<span>M</span> {{$event->getDays()}}<span>D</span> </p>
                  @if($event->isPastEvent())
                  <p class="text-2xl text-white text-shadow custom-font-style">
                    &LeftAngleBracket;Since&RightAngleBracket;</p>
                  @endif
                </div>
              </div>
              @else
              {{-- Gradient background card --}}
              <div
                class="py-16 aspect-w-1 aspect-h-1 bg-gradient-to-r from-purple-500 to-indigo-600 flex flex-col justify-center items-center text-center text-white">
                <h2 class="text-2xl font-bold mb-4">{{ $event->event_name }}</h2>
                <h2 class="text-xl font-semibold mb-2">{{ $event->event_date }}</h2>
                <p class="text-3xl text-white custom-font-style"> {{$event->getYears()}}<span>Y</span>
                  {{$event->getMonths()}}<span>M</span> {{$event->getDays()}}<span>D</span> </p>
                @if($event->isPastEvent())
                <p class="text-2xl text-white text-shadow custom-font-style">
                  &LeftAngleBracket;Since&RightAngleBracket;</p>
                @endif
              </div>
              @endif
              @endforeach
            </div>
            {{ $events->links() }}
          </div>
          @endif
      </div>
    </div>
  </div>
</x-app-layout>