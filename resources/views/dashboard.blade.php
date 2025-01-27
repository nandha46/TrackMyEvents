<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mt-10 grid gap-2 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2">
                        @foreach ($events as $event)
                        <div @class(['timecard', $event->background => ! $event->is_background_image, 'bg-cover
                            bg-center' => $event->is_background_image]) @if($event->is_background_image)
                            style="background-image:url('{{url($event->background)}}')" @endif>
                            <div class="text-wrapper">
                                <p class="text-4xl text-white custom-font-style">{{ $event->event_name }}</p>
                                <p class="text-2xl text-zinc-500 custom-font-style">{{ $event->event_date }}</p>
                                <p class="text-3xl text-white custom-font-style"> {{$event->getYears()}}<span>Y</span>
                                    {{$event->getMonths()}}<span>M</span> {{$event->getDays()}}<span>D</span> </p>
                                @if($event->isPastEvent())
                                <p class="text-2xl text-white text-shadow custom-font-style">
                                    &LeftAngleBracket;Since&RightAngleBracket;</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>