<div>
    <x-modal-child>
        <x-slot:title>
            {{ __("timer.edit") . ' ' . $user_to_edit['name'] }}
        </x-slot:title>

        <div class="lg:col-span-2 bg-day-1 dark:bg-night-6 flex items-center justify-center rounded shadow mb-4">
            @livewire('utils.avatar-upload', ['user' => $user_to_edit])
        </div>
        <x-forms.inputs.field wire:model="name" wire:keydown.enter="save" name="name" label="{{ __('timer.client_name') }}" value="{{ $user_to_edit['name'] }}" autofocus required />
        <x-forms.inputs.field wire:model="email" wire:keydown.enter="save" name="email" label="{{ __('timer.email') }}" value="{{ $user_to_edit['email'] }}" />
        <x-forms.inputs.field wire:model="locale" wire:keydown.enter="save" name="locale" label="{{ __('timer.locale') }}" value="{{ $user_to_edit['locale'] }}" />
        <x-forms.inputs.field type="checkbox" wire:model="is_admin" name="is_admin" label="{{ __('timer.is_admin') }}" :checked="0"></x-forms.inputs.field>

        <div class="mt-5 flex justify-between">
            <x-buttons.secondary wire:click="$dispatch('closeModal')">{{ __('timer.cancel') }}</x-buttons.secondary>
            <x-buttons.primary wire:click="save">{{ __('timer.save') }}</x-buttons.primary>
        </div>

    </x-modal-child>
</div>
