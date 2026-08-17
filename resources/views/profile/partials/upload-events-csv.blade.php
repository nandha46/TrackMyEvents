<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Import Events') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Import multiple events at once using a CSV or JSON file.') }}
        </p>
    </header>

    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-400 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <span>Need a template? Download a sample data file:</span>
        <div class="flex gap-2">
            <a href="{{ route('profile.template', 'csv') }}" class="px-2.5 py-1 bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-bold rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50">CSV Template</a>
            <a href="{{ route('profile.template', 'json') }}" class="px-2.5 py-1 bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 font-bold rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50">JSON Template</a>
        </div>
    </div>

    <form method="post" action="{{ route('profile.import') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="import_file" :value="__('Select File (.csv or .json)')" />
            <input id="import_file" name="import_file" type="file" accept=".csv,.json,.txt" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-indigo-400" required />
            <x-input-error class="mt-2" :messages="$errors->get('import_file')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Upload & Import') }}</x-primary-button>
        </div>
    </form>
</section>
