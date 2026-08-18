<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Private Events Security PIN') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Set a 4-digit PIN to protect and blur private countdowns. When unlocked, private events stay visible for 5 minutes.') }}
        </p>
    </header>

    <form method="post" action="{{ route('private.set_pin') }}" class="mt-6 space-y-6">
        @csrf

        @if(Auth::user()->hasPrivatePin())
            <div>
                <x-input-label for="current_pin" :value="__('Current 4-Digit PIN')" />
                <x-text-input id="current_pin" name="current_pin" type="password" maxlength="4" class="mt-1 block w-36 font-mono text-center tracking-widest text-lg" placeholder="••••" required autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePin->get('current_pin')" class="mt-2" />
            </div>
        @endif

        <div>
            <x-input-label for="pin" :value="__('New 4-Digit PIN')" />
            <x-text-input id="pin" name="pin" type="password" maxlength="4" class="mt-1 block w-36 font-mono text-center tracking-widest text-lg" placeholder="••••" required autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePin->get('pin')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="pin_confirmation" :value="__('Confirm 4-Digit PIN')" />
            <x-text-input id="pin_confirmation" name="pin_confirmation" type="password" maxlength="4" class="mt-1 block w-36 font-mono text-center tracking-widest text-lg" placeholder="••••" required autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePin->get('pin_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save PIN') }}</x-primary-button>

            @if (session('status') === 'pin-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('PIN Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
