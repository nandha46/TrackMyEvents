<x-app-layout>
    <div class="py-6 sm:py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Edit Event</h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">Update details for {{ $event->event_name }}</p>
                </div>
                <a href="{{ route('event.show', $event->id) }}" class="text-xs font-bold text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">&larr; Back to Event</a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <form action="{{ route('event.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6"
                      x-data="{
                          selectedTheme: '{{ $event->is_background_image ? "image" : "gradient" }}',
                          selectedGradient: '{{ str_starts_with($event->background ?? "", "gradient") ? $event->background : "gradient-1" }}',
                          recurrence: '{{ $event->recurrence_type ?? "none" }}'
                      }">
                    @csrf
                    @method('PUT')

                    <!-- Event Name -->
                    <div>
                        <label for="event_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Event Name</label>
                        <input type="text" name="event_name" id="event_name" value="{{ old('event_name', $event->event_name) }}" required
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500">
                        @error('event_name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Date & Time Picker -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="event_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Event Date</label>
                            <div class="relative">
                                <input type="text" name="event_date" id="event_date" x-datepicker value="{{ old('event_date', $event->event_date) }}" required
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                <svg class="w-4 h-4 text-gray-400 absolute right-4 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            @error('event_date') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="event_time" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Event Time (Optional)</label>
                            <div class="relative">
                                <input type="text" name="event_time" id="event_time" x-timepicker value="{{ old('event_time', $event->event_time) }}"
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                <svg class="w-4 h-4 text-gray-400 absolute right-4 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            @error('event_time') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Category (with Birthday) & Recurrence -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="event_type" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Category</label>
                            <select name="event_type" id="event_type" class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="Personal" {{ old('event_type', $event->event_type) === 'Personal' ? 'selected' : '' }}>Personal</option>
                                <option value="Birthday" {{ old('event_type', $event->event_type) === 'Birthday' ? 'selected' : '' }}>🎂 Birthday</option>
                                <option value="Work" {{ old('event_type', $event->event_type) === 'Work' ? 'selected' : '' }}>Work</option>
                                <option value="Reminder" {{ old('event_type', $event->event_type) === 'Reminder' ? 'selected' : '' }}>Reminder</option>
                                <option value="Private" {{ old('event_type', $event->event_type) === 'Private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>

                        <div>
                            <label for="recurrence_type" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Recurrence Schedule</label>
                            <select name="recurrence_type" id="recurrence_type" x-model="recurrence" class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="none">One-time Event (No Recurrence)</option>
                                <option value="yearly">Repeats Yearly (Birthday / Anniversary)</option>
                                <option value="monthly">Repeats Monthly</option>
                                <option value="weekly">Repeats Weekly</option>
                                <option value="daily">Repeats Daily</option>
                            </select>
                            <input type="hidden" name="is_recurring" :value="recurrence !== 'none' ? '1' : '0'">
                        </div>
                    </div>

                    <!-- Visual Theme Toggle (Gradient vs Photo Background) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">Visual Theme</label>
                        
                        <div class="inline-flex p-1 bg-gray-100 dark:bg-gray-900 rounded-2xl mb-4 w-full sm:w-auto">
                            <button type="button" @click="selectedTheme = 'gradient'"
                                    :class="selectedTheme === 'gradient' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-xs font-bold' : 'text-gray-500 dark:text-gray-400 font-medium'"
                                    class="flex-1 sm:flex-initial px-4 py-2 text-xs rounded-xl transition">
                                Gradient Colors
                            </button>
                            <button type="button" @click="selectedTheme = 'image'"
                                    :class="selectedTheme === 'image' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-xs font-bold' : 'text-gray-500 dark:text-gray-400 font-medium'"
                                    class="flex-1 sm:flex-initial px-4 py-2 text-xs rounded-xl transition">
                                Custom Photo Background
                            </button>
                        </div>

                        <!-- Hidden input explicitly sending 1 or 0 -->
                        <input type="hidden" name="is_background_image" :value="selectedTheme === 'image' ? '1' : '0'">

                        <!-- Gradient Palettes -->
                        <div x-show="selectedTheme === 'gradient'" class="grid grid-cols-4 gap-2.5">
                            @foreach([
                                'gradient-1' => 'from-purple-500 to-indigo-600',
                                'gradient-2' => 'from-blue-500 to-teal-500',
                                'gradient-3' => 'from-pink-500 to-rose-500',
                                'gradient-4' => 'from-amber-500 to-orange-600',
                                'gradient-5' => 'from-emerald-500 to-green-600',
                                'gradient-6' => 'from-cyan-500 to-blue-600',
                                'gradient-7' => 'from-violet-600 to-fuchsia-600',
                                'gradient-8' => 'from-rose-500 to-purple-600',
                            ] as $key => $grad)
                                <label @click="selectedGradient = '{{ $key }}'"
                                       :class="selectedGradient === '{{ $key }}' ? 'ring-4 ring-indigo-500 scale-105' : 'opacity-85 hover:opacity-100'"
                                       class="relative cursor-pointer rounded-2xl h-14 bg-gradient-to-r {{ $grad }} flex items-center justify-center transition-all shadow-xs">
                                    <input type="radio" name="background_gradient" value="{{ $key }}" x-model="selectedGradient" class="sr-only">
                                </label>
                            @endforeach
                        </div>

                        <!-- Image dropzone -->
                        <div x-show="selectedTheme === 'image'" x-data="dropzone('{{ $event->is_background_image ? asset('uploads/backgrounds/'.$event->background) : '' }}')" x-cloak class="mt-2">
                            <div @dragover.prevent="isDragging = true"
                                 @dragleave.prevent="isDragging = false"
                                 @drop.prevent="handleDrop($event)"
                                 :class="isDragging ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/20' : 'border-gray-300 dark:border-gray-600'"
                                 class="border-2 border-dashed rounded-3xl p-6 sm:p-8 text-center cursor-pointer hover:border-indigo-500 transition">
                                
                                <template x-if="!previewUrl">
                                    <div>
                                        <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 font-semibold">Drag & drop new background photo, or <span class="text-indigo-600 dark:text-indigo-400 underline">browse</span></p>
                                    </div>
                                </template>

                                <template x-if="previewUrl">
                                    <div class="relative max-w-xs mx-auto">
                                        <img :src="previewUrl" class="h-36 w-full object-cover rounded-2xl shadow-md">
                                        <button type="button" @click="clearPreview()" class="absolute -top-2 -right-2 bg-rose-600 text-white rounded-full p-1.5 shadow-md hover:bg-rose-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>

                                <input type="file" name="background_image" x-ref="fileInput" @change="handleFileSelect($event)" accept="image/*" class="sr-only">
                            </div>
                        </div>
                    </div>

                    <!-- Milestones raw edit -->
                    @php
                        $milestoneText = is_array($event->milestones) ? implode("\n", array_column($event->milestones, 'title')) : '';
                    @endphp
                    <div>
                        <label for="milestones_raw" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Sub-tasks / Milestones Checklist (One per line)</label>
                        <textarea name="milestones_raw" id="milestones_raw" rows="3"
                                  class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500">{{ old('milestones_raw', $milestoneText) }}</textarea>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Notes & Description</label>
                        <textarea name="description" id="description" rows="2"
                                  class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl shadow-lg shadow-indigo-600/25 transition text-sm">
                        Save Changes
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
