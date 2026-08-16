<x-app-layout>
  <div class="sm:max-w-3xl max-w-full mx-auto p-6 bg-white dark:bg-gray-800 rounded-md shadow-md">
    <div>
      <h2 class="text-2xl font-semibold mb-6 border-b pb-6 text-gray-900 dark:text-white">Edit Event</h2>
    </div>
    <form method="POST" class="max-w-2xl mx-auto" action="{{ route('event.update', $event->id) }}" enctype="multipart/form-data" x-data="{ useImage: {{ $event->is_background_image ? 'true' : 'false' }} }">
      @csrf
      @method('PUT')
      <div class="mb-5">
        <label for="eventName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event Name</label>
        <input type="text" id="eventName" name="event_name"
          class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
          placeholder="Enter event name" value="{{ old('event_name', $event->event_name) }}" required />
        <x-input-error class="mt-2" :messages="$errors->get('event_name')" />
      </div>
      <div class="flex justify-evenly gap-4">
        <div class="mb-4 w-full">
          <label for="eventDate" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Date</label>
          <input type="date" id="eventDate" name="event_date" value="{{ old('event_date', $event->event_date) }}" required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:text-white">
          <x-input-error class="mt-2" :messages="$errors->get('event_date')" />
        </div>
        <div class="mb-4 w-full">
          <label for="eventTime" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Time (optional)</label>
          <input type="time" id="eventTime" name="event_time" value="{{ old('event_time', $event->event_time ? date('H:i', strtotime($event->event_time)) : '') }}"
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:text-white">
          <x-input-error class="mt-2" :messages="$errors->get('event_time')" />
        </div>
      </div>
      <div class="mb-6">
        <label for="eventType" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Event Type</label>
        <select id="eventType" name="event_type"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
          @foreach(['Personal', 'Private', 'Work', 'Reminder', 'Past', 'Future'] as $type)
          <option value="{{ $type }}" {{ old('event_type', $event->event_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
          @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('event_type')" />
      </div>
      <div class="mb-5">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Time Display Units</label>
        <fieldset class="flex flex-wrap gap-4">
          <legend class="sr-only">Time display fields selection</legend>
          @php $selectedFields = explode(',', old('fields', $event->fields ?? 'days,months,years')); @endphp
          @foreach(['days', 'months', 'years', 'hours', 'weeks', 'minutes', 'seconds', 'workdays'] as $field)
          <div class="flex items-center">
            <input id="field-{{ $field }}" type="checkbox" value="{{ $field }}" name="fields[]"
              {{ in_array($field, $selectedFields) ? 'checked' : '' }}
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
            <label for="field-{{ $field }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ ucfirst($field) }}</label>
          </div>
          @endforeach
        </fieldset>
        <x-input-error class="mt-2" :messages="$errors->get('fields')" />
      </div>
      <div class="mb-5">
        <label class="inline-flex items-center cursor-pointer">
          <input type="checkbox" value="1" name="is_background_image" x-model="useImage" class="sr-only peer">
          <div
            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
          </div>
          <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Use Background Image</span>
        </label>
      </div>

      <div class="mb-5" x-show="useImage" x-cloak>
        @if($event->is_background_image)
          <div class="mb-3">
            <span class="text-xs text-gray-500 block mb-1">Current Background:</span>
            <img src="{{ asset('uploads/backgrounds/'.$event->background) }}" alt="Current background" class="h-24 w-auto rounded object-cover">
          </div>
        @endif
        <div class="flex items-center justify-center w-full">
          <label for="dropzone-file"
            class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
              <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
              </svg>
              <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to replace background image</span></p>
              <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG, WEBP (MAX. 2MB)</p>
            </div>
            <input id="dropzone-file" type="file" name="background_image" accept="image/*" class="hidden" />
          </label>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('background_image')" />
      </div>

      <div id="gradientSelectorSection" class="mb-5" x-show="!useImage">
        <label for="background_gradient" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Background Gradient</label>
        <select id="background_gradient" name="background_gradient"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
          <option value="from-purple-500 to-indigo-600" {{ $event->background === 'from-purple-500 to-indigo-600' ? 'selected' : '' }}>Purple to Indigo</option>
          <option value="from-blue-500 to-teal-500" {{ $event->background === 'from-blue-500 to-teal-500' ? 'selected' : '' }}>Blue to Teal</option>
          <option value="from-pink-500 to-rose-500" {{ $event->background === 'from-pink-500 to-rose-500' ? 'selected' : '' }}>Pink to Rose</option>
          <option value="from-amber-500 to-orange-600" {{ $event->background === 'from-amber-500 to-orange-600' ? 'selected' : '' }}>Amber to Orange</option>
          <option value="from-emerald-500 to-green-600" {{ $event->background === 'from-emerald-500 to-green-600' ? 'selected' : '' }}>Emerald to Green</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('background_gradient')" />
      </div>

      <div class="flex justify-end gap-2">
        <a href="{{ route('event.show', $event->id) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md">Cancel</a>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">Save Changes</button>
      </div>
    </form>
  </div>
</x-app-layout>
