<a href="{{ $href }}" 
     class="mr-4 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-1 px-2 rounded flex items-center justify-center inline-flex">
    <x-Icons.plus />
    <span {{ $attributes->merge(['class' => '']) }}>
        {{ $slot }}
    </span> 
</a>