<x-app-layout>
    <div class="py-6 sm:py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Create New Event</h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">Add a countdown or milestone to track in your dashboard</p>
                </div>
                <a href="{{ route('dashboard') }}" class="text-xs font-bold text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">&larr; Cancel</a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <form action="{{ route('event.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6"
                      x-data="{
                          selectedTheme: 'gradient',
                          selectedGradient: 'gradient-1',
                          recurrence: 'none'
                      }">
                    @csrf

                    <!-- Event Name -->
                    <div>
                        <label for="event_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Event Name</label>
                        <input type="text" name="event_name" id="event_name" value="{{ old('event_name') }}" required
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 placeholder-gray-400"
                               placeholder="e.g., Summer Vacation to Hawaii or Mom's Birthday">
                        @error('event_name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Modern Date & Time Picker -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="event_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Event Date</label>
                            <div class="relative">
                                <input type="text" name="event_date" id="event_date" x-datepicker value="{{ old('event_date', date('Y-m-d')) }}" required
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                <svg class="w-4 h-4 text-gray-400 absolute right-4 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            @error('event_date') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="event_time" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Event Time (Optional)</label>
                            <div class="relative">
                                <input type="text" name="event_time" id="event_time" x-timepicker value="{{ old('event_time', '10:00') }}"
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                <svg class="w-4 h-4 text-gray-400 absolute right-4 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            @error('event_time') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Category & Recurrence -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="event_type" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Category / Privacy</label>
                            <select name="event_type" id="event_type" class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="Personal" {{ old('event_type') === 'Personal' ? 'selected' : '' }}>Personal</option>
                                <option value="Birthday" {{ old('event_type') === 'Birthday' ? 'selected' : '' }}>🎂 Birthday</option>
                                <option value="Work" {{ old('event_type') === 'Work' ? 'selected' : '' }}>Work</option>
                                <option value="Reminder" {{ old('event_type') === 'Reminder' ? 'selected' : '' }}>Reminder</option>
                                <option value="Private" {{ old('event_type') === 'Private' ? 'selected' : '' }}>🔒 Private (PIN Protected)</option>
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

                    <!-- Tags Input (Interactive Tag Chips) -->
                    <div x-data="{
                        tagsStr: '{{ old('tags', '') }}',
                        addTag(tag) {
                            let current = this.tagsStr.split(',').map(t => t.trim()).filter(Boolean);
                            if (!current.includes(tag)) {
                                current.push(tag);
                                this.tagsStr = current.join(', ');
                            }
                        }
                    }">
                        <label for="tags" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Tags (Organize your events)</label>
                        <input type="text" name="tags" id="tags" x-model="tagsStr"
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 placeholder-gray-400"
                               placeholder="e.g. vacation, summer, family, milestone, travel">
                        
                        <!-- Quick Click Suggestions -->
                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                            <span class="text-[10px] text-gray-400 font-bold">Popular tags:</span>
                            @foreach(['Vacation', 'Travel', 'Family', 'Fitness', 'Milestone', 'Finance', 'Party', 'Meeting', 'Anniversary'] as $tag)
                                <button type="button" @click="addTag('{{ $tag }}')"
                                        class="px-2.5 py-0.5 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 text-gray-600 dark:text-gray-300 hover:text-indigo-600 text-[11px] font-bold transition">
                                    + #{{ $tag }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Visual Theme Toggle (Gradient vs Custom Photo vs Pexels) -->
                    <div x-data="pexelsPicker()">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">Visual Theme</label>
                        
                        <!-- Segmented control switch -->
                        <div class="inline-flex p-1 bg-gray-100 dark:bg-gray-900 rounded-2xl mb-4 w-full sm:w-auto">
                            <button type="button" @click="selectedTheme = 'gradient'"
                                    :class="selectedTheme === 'gradient' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-xs font-bold' : 'text-gray-500 dark:text-gray-400 font-medium'"
                                    class="flex-1 sm:flex-initial px-4 py-2 text-xs rounded-xl transition">
                                Gradient Colors
                            </button>
                            <button type="button" @click="selectedTheme = 'image'"
                                    :class="selectedTheme === 'image' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-xs font-bold' : 'text-gray-500 dark:text-gray-400 font-medium'"
                                    class="flex-1 sm:flex-initial px-4 py-2 text-xs rounded-xl transition">
                                Custom Photo Upload
                            </button>
                            <button type="button" @click="selectedTheme = 'pexels'; loadContextualOrCurated(document.getElementById('event_name')?.value)"
                                    :class="selectedTheme === 'pexels' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-xs font-bold' : 'text-gray-500 dark:text-gray-400 font-medium'"
                                    class="flex-1 sm:flex-initial px-4 py-2 text-xs rounded-xl transition flex items-center justify-center gap-1.5">
                                <span>Pexels Photos</span>
                                <span class="text-[9px] px-1.5 py-0.2 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 rounded font-black">Free</span>
                            </button>
                        </div>

                        <input type="hidden" name="is_background_image" :value="selectedTheme !== 'gradient' ? '1' : '0'">

                        <!-- Hidden Pexels Payload Inputs -->
                        <input type="hidden" name="pexels_id" :value="selectedTheme === 'pexels' && selectedPhoto ? selectedPhoto.id : ''">
                        <input type="hidden" name="pexels_photographer" :value="selectedTheme === 'pexels' && selectedPhoto ? selectedPhoto.photographer : ''">
                        <input type="hidden" name="pexels_photographer_url" :value="selectedTheme === 'pexels' && selectedPhoto ? selectedPhoto.photographer_url : ''">
                        <input type="hidden" name="pexels_photo_url" :value="selectedTheme === 'pexels' && selectedPhoto ? selectedPhoto.url : ''">
                        <input type="hidden" name="pexels_download_url" :value="selectedTheme === 'pexels' && selectedPhoto ? (selectedPhoto.src?.large || selectedPhoto.src?.original) : ''">

                        <!-- 1. Gradient Palettes -->
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

                        <!-- 2. Custom Drag and drop image dropzone -->
                        <div x-show="selectedTheme === 'image'" x-data="dropzone()" x-cloak class="mt-2">
                            <div @dragover.prevent="isDragging = true"
                                 @dragleave.prevent="isDragging = false"
                                 @drop.prevent="handleDrop($event)"
                                 :class="isDragging ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/20' : 'border-gray-300 dark:border-gray-600'"
                                 class="border-2 border-dashed rounded-3xl p-6 sm:p-8 text-center cursor-pointer hover:border-indigo-500 transition">
                                
                                <template x-if="!previewUrl">
                                    <div>
                                        <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 font-semibold">Drag & drop your background photo here, or <span class="text-indigo-600 dark:text-indigo-400 underline">browse</span></p>
                                        <p class="text-[10px] text-gray-400 mt-1">Supports JPG, PNG, WEBP up to 4MB</p>
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

                        <!-- 3. Pexels Gallery Picker -->
                        <div x-show="selectedTheme === 'pexels'" x-cloak class="mt-2 space-y-4">
                            <!-- Search / Filter header -->
                            <div class="flex flex-col sm:flex-row gap-2 items-center justify-between">
                                <div class="relative w-full">
                                    <input type="text" x-model.debounce.400ms="query" placeholder="Search photos (e.g. Birthday, Tokyo, Concert)..."
                                           class="w-full pl-9 pr-4 py-2.5 text-xs rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white">
                                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-[11px] font-bold text-gray-500 dark:text-gray-400" x-text="isCurated ? 'Curated Photos' : 'Search Results'"></span>
                                    <a href="https://www.pexels.com" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-[10px] text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        <span>Photos by Pexels</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Selected Preview Banner -->
                            <template x-if="selectedPhoto">
                                <div class="p-3 bg-indigo-50 dark:bg-indigo-950/40 rounded-2xl border border-indigo-200 dark:border-indigo-800 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <img :src="selectedPhoto.src?.medium || selectedPhoto.src?.small" class="w-16 h-12 rounded-xl object-cover shadow-xs">
                                        <div>
                                            <span class="text-xs font-black text-indigo-700 dark:text-indigo-300 block">Selected Background</span>
                                            <span class="text-[10px] text-gray-600 dark:text-gray-400">Photo by <span class="font-bold" x-text="selectedPhoto.photographer"></span></span>
                                        </div>
                                    </div>
                                    <button type="button" @click="clearSelection()" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-bold px-2">
                                        Remove
                                    </button>
                                </div>
                            </template>

                            <!-- Loading Spinner -->
                            <div x-show="loading" class="py-8 text-center text-xs text-gray-500">
                                <svg class="animate-spin h-6 w-6 text-indigo-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Loading photos from Pexels...</span>
                            </div>

                            <!-- Error Message -->
                            <div x-show="!loading && errorMessage" class="p-3 bg-rose-50 dark:bg-rose-950/30 text-rose-600 text-xs rounded-xl" x-text="errorMessage"></div>

                            <!-- Photos Grid -->
                            <div x-show="!loading && photos.length > 0" class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 max-h-72 overflow-y-auto pr-1">
                                <template x-for="photo in photos" :key="photo.id">
                                    <div @click="selectPhoto(photo)"
                                         :class="selectedPhoto && selectedPhoto.id === photo.id ? 'ring-4 ring-indigo-500 scale-[1.02]' : 'hover:opacity-90'"
                                         class="relative h-24 rounded-2xl overflow-hidden cursor-pointer group shadow-xs border border-gray-200 dark:border-gray-700 bg-gray-900 transition-all">
                                        <img :src="photo.src.medium" :alt="photo.alt" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-80 group-hover:opacity-100 flex items-end p-2 transition-opacity">
                                            <span class="text-[9px] text-white font-bold truncate block" x-text="photo.photographer"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Pagination Controls -->
                            <div x-show="!loading && photos.length > 0" class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-700/60 text-xs">
                                <button type="button" @click="prevPage()" :disabled="page <= 1"
                                        :class="page <= 1 ? 'opacity-40 cursor-not-allowed' : 'hover:text-indigo-600'"
                                        class="font-bold text-gray-600 dark:text-gray-300">
                                    &larr; Previous
                                </button>
                                <span class="text-[10px] text-gray-400" x-text="'Page ' + page"></span>
                                <button type="button" @click="nextPage()"
                                        class="font-bold text-gray-600 dark:text-gray-300 hover:text-indigo-600">
                                    Next &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Milestones Checklist input -->
                    <div>
                        <label for="milestones" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Sub-tasks / Milestones Checklist (Optional)</label>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-1.5">Enter each checklist task on a new line</p>
                        <textarea name="milestones" id="milestones" rows="3"
                                  class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 placeholder-gray-400"
                                  placeholder="Book flight tickets&#10;Reserve hotel&#10;Order cake"></textarea>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Notes & Description (Optional)</label>
                        <textarea name="description" id="description" rows="2"
                                  class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 placeholder-gray-400"
                                  placeholder="Add any extra notes or memories..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl shadow-lg shadow-indigo-600/25 transition text-sm">
                        Create Event & Start Tracking
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
