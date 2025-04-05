<x-app-layout>
<div class="max-w-md mx-auto p-6 bg-white rounded-md shadow-md">
  <h2 class="text-2xl font-semibold mb-6">Add New Event</h2>

  <form method="POST" action="{{ route('event.store') }}">
    @csrf
    <div class="mb-4">
      <label for="eventName" class="block text-gray-700 text-sm font-bold mb-2">Event Name</label>
      <input type="text" id="eventName" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" placeholder="Enter event name">
    </div>

    <div class="mb-4">
      <label for="eventDate" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
      <input type="date" id="eventDate" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <div class="mb-4">
      <label for="eventTime" class="block text-gray-700 text-sm font-bold mb-2">Time</label>
      <input type="time" id="eventTime" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <div class="mb-4">
      <label for="eventType" class="block text-gray-700 text-sm font-bold mb-2">Event Type</label>
      <select id="eventType" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        <option value="meeting">Meeting</option>
        <option value="conference">Conference</option>
        <option value="workshop">Workshop</option>
        <option value="party">Party</option>
      </select>
    </div>

    <div class="mb-4">
      <label for="eventFields" class="block text-gray-700 text-sm font-bold mb-2">Fields (Multiple)</label>
      <select id="eventFields" multiple class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        <option value="location">Location</option>
        <option value="speakers">Speakers</option>
        <option value="agenda">Agenda</option>
        <option value="participants">Participants</option>
      </select>
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">Background</label>
      <div class="flex items-center">
        <span class="mr-2">Image</span>
        <label class="relative inline-flex items-center cursor-pointer">
          <input type="checkbox" value="" id="backgroundToggle" class="sr-only peer">
          <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:p-0.5 after:transition-all peer-checked:bg-blue-600"></div>
        </label>
        <span class="ml-2">Gradient</span>
      </div>

      <div id="imageUploadSection" class="mt-2">
        <input type="file" id="imageUpload" accept="image/*" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        <img id="imagePreview" class="mt-2 max-w-full hidden" alt="Image Preview">
      </div>

      <div id="gradientSelectorSection" class="mt-2 hidden">
        <input type="text" id="gradientInput" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" placeholder="Enter gradient (e.g., linear-gradient(to right, red, yellow))">
      </div>

    </div>

    <div class="flex justify-end">
      <button type="button" class="px-4 py-2 bg-gray-200 rounded-md mr-2">Cancel</button>
      <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Submit</button>
    </div>
  </form>
</div>
</x-app-layout>