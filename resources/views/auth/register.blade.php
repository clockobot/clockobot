<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-auth.input-label for="name" :value="__('Nom')" class="text-day-7 dark:text-night-1" />
            <x-auth.text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-auth.input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-auth.input-label for="email" :value="__('Email')" class="text-day-7 dark:text-night-1" />
            <x-auth.text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-auth.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-auth.input-label for="password" :value="__('Mot de passe')" class="text-day-7 dark:text-night-1" />

            <x-auth.text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-auth.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-auth.input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-day-7 dark:text-night-1" />

            <x-auth.text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-auth.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline-offset-4 underline text-sm text-day-7 dark:text-night-1 rounded-md" href="{{ route('login') }}">
                {{ __('Déjà enregistré ?') }}
            </a>

            <x-auth.primary-button class="ml-4">
                {{ __('Créer un compte') }}
            </x-auth.primary-button>
        </div>
    </form>
</x-guest-layout>
