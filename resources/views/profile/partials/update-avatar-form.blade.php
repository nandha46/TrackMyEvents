<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Avatar') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your avatar image') }}
        </p>
    </header>
    <form method="POST" action="{{ route('profile.avatar.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="columns-2 w-auto">
            <div class="image-container">
                <img class="inline-block sm:size-64 md:size-42 lg:size-21 ring-white" alt="{{ Auth::user()->name }}" src="{{ asset('uploads/profile_pictures/'.Auth::user()->avatar) }}">
            </div>
            <div
      class="mt-4 bg-gray-50 text-center px-4 rounded max-w-md flex flex-col items-center justify-center cursor-pointer border-2 border-gray-300 border-dashed mx-auto">
      <div class="py-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 mb-4 fill-slate-600 inline-block" viewBox="0 0 32 32">
          <path
            d="M23.75 11.044a7.99 7.99 0 0 0-15.5-.009A8 8 0 0 0 9 27h3a1 1 0 0 0 0-2H9a6 6 0 0 1-.035-12 1.038 1.038 0 0 0 1.1-.854 5.991 5.991 0 0 1 11.862 0A1.08 1.08 0 0 0 23 13a6 6 0 0 1 0 12h-3a1 1 0 0 0 0 2h3a8 8 0 0 0 .75-15.956z"
            data-original="#000000" />
          <path
            d="M20.293 19.707a1 1 0 0 0 1.414-1.414l-5-5a1 1 0 0 0-1.414 0l-5 5a1 1 0 0 0 1.414 1.414L15 16.414V29a1 1 0 0 0 2 0V16.414z"
            data-original="#000000" />
        </svg>
        <h4 class="text-base font-semibold text-slate-600">Drag and drop files here</h4>
      </div>

      <hr class="w-full border-gray-300 my-2" />

      <div class="py-6">
        <input type="file" id='uploadFile1' class="hidden" />
        <label for="uploadFile1"
          class="block px-6 py-2.5 rounded text-slate-600 text-sm tracking-wider font-semibold border-none outline-none cursor-pointer bg-gray-200 hover:bg-gray-100">Browse
          Files</label>
        <p class="text-xs text-slate-500 mt-4">PNG, JPG SVG, WEBP, and GIF are Allowed.</p>
      </div>
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