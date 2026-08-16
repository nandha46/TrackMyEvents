<form wire:submit="save" class="mt-6 space-y-6">
    <div>
        <x-input-label for="csvfile" :value="__('Select CSV File')" />
        <input type="file" id="csvfile" wire:model="csvfile" accept=".csv,text/csv,text/plain" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
        <x-input-error class="mt-2" :messages="$errors->get('csvfile')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('Import') }}</span>
            <span wire:loading>{{ __('Importing...') }}</span>
        </x-primary-button>

        @if ($errorMessage)
            <p class="text-sm text-red-600 dark:text-red-400">{{ $errorMessage }}</p>                
        @endif

        @if ($message)
            <p class="text-sm text-emerald-600 dark:text-emerald-400">{{ $message }}</p>
        @endif
    </div>
</form>