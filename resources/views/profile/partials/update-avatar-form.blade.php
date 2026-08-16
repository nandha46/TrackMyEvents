<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Avatar') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your avatar image') }}
        </p>
    </header>
    <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6" x-data="{ previewUrl: null }">
        @csrf
        @method('patch')

        <div class="flex flex-col sm:flex-row items-center gap-6">
            <div class="image-container">
                <template x-if="previewUrl">
                    <img class="inline-block w-32 h-32 rounded-full object-cover ring-4 ring-indigo-500" :src="previewUrl" alt="Avatar preview">
                </template>
                <template x-if="!previewUrl">
                    <img class="inline-block w-32 h-32 rounded-full object-cover ring-2 ring-gray-300 dark:ring-gray-600" alt="{{ Auth::user()->name }}" src="{{ asset('uploads/profile_pictures/'.Auth::user()->avatar) }}">
                </template>
            </div>
            <div class="flex-1 w-full">
                <label for="avatarInput"
                    class="flex flex-col items-center justify-center p-6 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600">
                    <svg class="w-8 h-8 mb-2 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Click to browse or replace avatar</span>
                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG, WEBP or SVG (MAX. 2MB)</p>
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" @change="previewUrl = URL.createObjectURL($event.target.files[0])" />
                </label>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-avatar-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>