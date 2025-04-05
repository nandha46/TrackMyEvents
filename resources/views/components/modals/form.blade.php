{{-- Modal form start --}}
<div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-show="open"
  x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
  x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

  <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      <div
        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
        x-show="open" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div
              class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
              <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Add New Event</h3>
              <form class="max-w-md mx-auto p-6 bg-white shadow-md rounded-lg space-y-40"
                method="POST" action="{{ route('event.store') }}"
                x-data="{ showImageUpload: false, imagePreview: null, gradientStart: '#ff0000', gradientEnd: '#0000ff' }">
                @csrf
                <!-- Name -->
                <div>
                  <label class="block text-gray-700">Event Name</label>
                  <input type="text" name="event_name" class="w-full p-2 border rounded-md" placeholder="Enter your name" />
                </div>

                <!-- Date and Time Side by Side -->
                <div class="flex space-x-4">
                  <div class="w-1/2">
                    <label class="block text-gray-700">Date</label>
                    <input type="date" name="event_date" class="w-full p-2 border rounded-md" />
                  </div>
                  <div class="w-1/2">
                    <label class="block text-gray-700">Time</label>
                    <input type="time" name="event_time" class="w-full p-2 border rounded-md" />
                  </div>
                </div>

                <!-- Dropdown -->
                <div>
                  <label class="block text-gray-700">Event Type</label>
                  <select class="w-full p-2 border rounded-md" name="event_type">
                    <option>Personal</option>
                    <option>Work</option>
                    <option>Reminder</option>
                  </select>
                </div>

                <!-- Multi-Select Checkbox using Flexbox -->
                <div>
                  <label class="block text-gray-700">Choose Fields</label>
                  <div class="flex space-x-4">
                    <label class="flex items-center space-x-2">
                      <input type="checkbox" class="form-checkbox" name="fields"/>
                      <span>Minutes</span>
                    </label>
                    <label class="flex items-center space-x-2">
                      <input type="checkbox" class="form-checkbox" />
                      <span>Hours</span>
                    </label>
                    <label class="flex items-center space-x-2">
                      <input type="checkbox" class="form-checkbox" />
                      <span>Days</span>
                    </label>
                  </div>
                </div>

                <!-- Switch -->
                <div>
                  <label class="block text-gray-700">Select Background</label>
                  <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_background_image" x-model="showImageUpload" class="sr-only peer">
                    <div
                      class="w-10 h-5 bg-gray-300 rounded-full peer-checked:bg-blue-500 relative before:absolute before:w-4 before:h-4 before:bg-white before:rounded-full before:top-0.5 before:left-0.5 before:transition-transform before:duration-300 peer-checked:before:translate-x-5">
                    </div>
                  </label>
                </div>

                <!-- Gradient Picker -->
                <div x-show="!showImageUpload">
                  <label class="block text-gray-700">Select Gradient</label>
                  <div class="flex space-x-2">
                    <div class="w-1/2">
                      <label class="block text-gray-600 text-sm">Start Color</label>
                      <input type="color" name="background" x-model="gradientStart" class="w-full h-10 p-2 border rounded-md" />
                    </div>
                    <div class="w-1/2">
                      <label class="block text-gray-600 text-sm">End Color</label>
                      <input type="color" x-model="gradientEnd" class="w-full h-10 p-2 border rounded-md" />
                    </div>
                  </div>
                  <div class="mt-2 w-full h-10 rounded-md border"
                    :style="'background: linear-gradient(to right, ' + gradientStart + ', ' + gradientEnd + ')'">
                  </div>

                </div>

                <!-- Image Upload -->
                <div x-show="showImageUpload">
                  <label class="block text-gray-700">Upload Background Image</label>
                  <input type="file" class="w-full p-2 border rounded-md"
                    @change="let file = $event.target.files[0]; if (file) { let reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL(file); }" />

                  <!-- Image Preview -->
                  <div x-show="imagePreview" class="mt-4">
                    <label class="block text-gray-700">Preview</label>
                    <img :src="imagePreview" class="w-full h-auto border rounded-md" />
                  </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                  <button type="submit"
                    class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Create</button>
                  <button type="button" @click="open = false"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- Modal form end --}}