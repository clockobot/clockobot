<div>
    <x-modal-child>
        <x-slot:title>
            {{ __('timer.delete_time_entries') }}
        </x-slot:title>

        <p class="my-4">{{ __('timer.delete_time_entries_warning') }}</p>

        <div class="mt-5 flex justify-between">
            <x-buttons.secondary wire:click="$dispatch('closeModal')">{{ __('timer.cancel') }}</x-buttons.secondary>
            <x-buttons.primary wire:click="confirmDelete">{{ __('timer.save') }}</x-buttons.primary>
        </div>

    </x-modal-child>
</div>
