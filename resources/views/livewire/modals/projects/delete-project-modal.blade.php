<div>
    <x-modal-child>
        <x-slot:title>
            {{ __("timer.delete_project") }}
        </x-slot:title>

        <p class="my-4">
            {{ __('timer.confirm_delete') }} <span class="font-bold text-app">{{ $project_to_delete->title }}</span> ?
            <br><br>
            <span class="underline-offset-2 underline">{{ __('timer.delete_project_warning') }}</span>
        </p>

        <div class="mt-5 flex justify-between">
            <x-buttons.secondary wire:click="$dispatch('closeModal')">{{ __("timer.cancel") }}</x-buttons.secondary>
            <x-buttons.primary wire:click="confirmDelete">{{ __("timer.delete") }}</x-buttons.primary>
        </div>

    </x-modal-child>
</div>
