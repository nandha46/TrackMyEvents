<x-app-layout>
  <div class="sm:max-w-3xl max-w-full mx-auto p-6 bg-white rounded-md shadow-md">
    <div>
      <h2 class="text-2xl font-semibold mb-6 border-b pb-6">Add New Event</h2>
    </div>
    <form method="POST" class="max-w-2xl mx-auto" action="{{ route('event.store') }}">
      @csrf
      <div class="mb-5">
        <label for="eventName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event Name</label>
        <input type="text" id="eventName" name="event_name"
          class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light"
          placeholder="Enter event name" value="{{ old('event_name') }}" required />
      </div>
      <div class="flex justify-evenly">
        <div class="mb-4 w-full pe-5">
          <label for="eventDate" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
          <input type="date" id="eventDate" name="event_date" value="{{ old('event_date') }}"
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="mb-4 w-full">
          <label for="eventTime" class="block text-gray-700 text-sm font-bold mb-2">Time</label>
          <input type="time" id="eventTime" name="event_time" value="{{ old('event_time') }}"
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
      </div>
      <div class="mb-6">
        <label for="eventType" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Event
          Type</label>
        <select id="eventType" name="event_type" value="{{ old('event_type') }}"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
          <option>Personal</option>
          <option>Private</option>
          <option>Work</option>
          <option>Reminder</option>
          <option>Past</option>
          <option>Future</option>
        </select>
      </div>
      <div class="mb-5">
        <fieldset class="flex">
          <legend class="sr-only">time fields selection</legend>
          <div class="flex items-center mb-4 me-4">
            <input checked id="checkbox-1" type="checkbox" value="days" name="checkbox-1"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="checkbox-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">days</label>
          </div>
          <div class="flex items-center mb-4 me-4">
            <input id="checkbox-2" type="checkbox" value="months" name="checkbox-2"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="checkbox-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">months</label>
          </div>
          <div class="flex items-center mb-4 me-4">
            <input id="checkbox-3" type="checkbox" value="years" name="checkbox-3"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="checkbox-3" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">years</label>
          </div>
          <div class="flex items-center mb-4 me-4">
            <input id="checkbox-4" type="checkbox" value="hours" name="checkbox-4"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="checkbox-4" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">hours</label>
          </div>
          <div class="flex items-center mb-4 me-4">
            <input id="checkbox-5" type="checkbox" value="weeks" name="checkbox-5"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="checkbox-5" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">weeks</label>
          </div>
          <div class="flex items-center mb-4 me-4">
            <input id="checkbox-6" type="checkbox" value="minutes" name="checkbox-6"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="checkbox-6" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">minutes</label>
          </div>
          <div class="flex items-center mb-4 me-4">
            <input id="checkbox-7" type="checkbox" value="seconds" name="checkbox-7"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="checkbox-7" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">seconds</label>
          </div>
          <div class="flex items-center mb-4 me-4">
            <input id="checkbox-8" type="checkbox" value="workdays" name="checkbox-8"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="checkbox-8" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">workdays</label>
          </div>
        </fieldset>
      </div>
      <div class="mb-5">
        <label class="inline-flex items-center mb-5 cursor-pointer">
          <input type="checkbox" value="" name="toggle" class="sr-only peer">
          <div
            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
          </div>
          <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Toggle me</span>
        </label>
      </div>

      <div class="mb-5">
        <div class="flex items-center justify-center w-full">
          <label for="dropzone-file"
            class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
              <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
              </svg>
              <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to
                  upload</span> or drag and drop</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
            </div>
            <input id="dropzone-file" type="file" name="file-1" class="hidden" />
          </label>
        </div>
      </div>

      <div id="gradientSelectorSection" class="mb-5 mt-2">
        <input type="text" id="gradientInput" name="gradient-1"
          class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
          placeholder="Enter gradient (e.g., linear-gradient(to right, red, yellow))">
      </div>

      <div class="flex justify-end">
        <button type="button" class="px-4 py-2 bg-gray-200 rounded-md mr-2">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Submit</button>
      </div>
    </form>
  </div>
</x-app-layout>