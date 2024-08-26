<x-guest-layout>
    <div class="mb-4 text-sm text-day-7 dark:text-night-1">
        Oublié votre mot de passe ? Pas de problème. Il vous suffit de nous indiquer votre adresse e-mail, et nous vous enverrons un lien de réinitialisation de mot de passe par e-mail qui vous permettra d'en choisir un nouveau.
{{--        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}--}}
    </div>

    <!-- Session Status -->
    <x-auth.auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-auth.input-label for="email" :value="__('Email')" class="text-day-7 dark:text-night-1" />
            <x-auth.text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-auth.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-auth.primary-button>
                {{ __('Envoyer un lien par email') }}
            </x-auth.primary-button>
        </div>
    </form>
</x-guest-layout>
