<div>
    <x-modal-child>
        <x-slot:title>
            {{ __("timer.delete_client") }}
        </x-slot:title>

        <p class="my-4">
            {{ __('timer.confirm_delete') }} <span class="font-bold text-app">{{ $client_to_delete['name'] }}</span> ?
            <br><br>
            <span class="italic underline-offset-2 underline">{{ __('timer.delete_client_warning') }}</span>
        </p>

        <div class="mt-5 flex justify-between">
            <x-buttons.secondary wire:click="$dispatch('closeModal')">{{ __("timer.cancel") }}</x-buttons.secondary>
            <x-buttons.primary wire:click="confirmDelete">{{ __("timer.delete") }}</x-buttons.primary>
        </div>

    </x-modal-child>
</div>
