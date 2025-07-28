<form wire:submit="save" class="mt-6 space-y-6">
    <div>
        <x-input-label for="csvfile" />
        <input type="file" id="csvfile" wire:model="csvfile">
        <x-input-error class="mt-2" :messages="$errors->get('message')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Import') }}</x-primary-button>


        @error('csvfile')
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $errorMessage }}</p>                
        @enderror

        @if (session('status') === 'Events imported.')
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $message }}</p>
        @endif
    </div>
</form>