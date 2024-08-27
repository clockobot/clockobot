<x-guest-layout>
    <div class="mb-4 text-sm text-day-7 dark:text-night-1">
        {{ __('timer.forgotten_password') }}
    </div>

    <!-- Session Status -->
    <x-auth.auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-auth.input-label for="email" :value="__('timer.email')" class="text-day-7 dark:text-night-1" />
            <x-auth.text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-auth.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-auth.primary-button>
                {{ __('timer.send_link') }}
            </x-auth.primary-button>
        </div>
    </form>
</x-guest-layout>
