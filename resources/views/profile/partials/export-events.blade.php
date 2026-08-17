<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Export Events') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Download a copy of your events data in CSV or JSON format with optional filtering.') }}
        </p>
    </header>

    <form method="get" action="{{ route('profile.export') }}" class="mt-6 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl">
            <div>
                <x-input-label for="export_format" :value="__('Format')" />
                <select id="export_format" name="format" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                    <option value="csv">CSV Spreadsheet (.csv)</option>
                    <option value="json">JSON Data Feed (.json)</option>
                </select>
            </div>

            <div>
                <x-input-label for="export_filter" :value="__('Filter by Range / Category')" />
                <select id="export_filter" name="filter" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                    <option value="all">All Events</option>
                    <option value="upcoming">Upcoming Events Only</option>
                    <option value="past">Past Events Only</option>
                    <option value="Birthday">Birthday Category</option>
                    <option value="Personal">Personal Category</option>
                    <option value="Work">Work Category</option>
                    <option value="Reminder">Reminder Category</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Export & Download') }}</x-primary-button>
        </div>
    </form>
</section>
