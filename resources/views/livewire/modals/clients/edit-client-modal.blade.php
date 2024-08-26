<div>
    <x-modal-child>
        <x-slot:title>
            {{ __("timer.edit") . ' ' . $client_to_edit['name'] }}
        </x-slot:title>
        <x-forms.inputs.field wire:model="name" wire:keydown.enter="save" name="name" label="{{ __('timer.client_name') }}" value="{{ $client_to_edit['name'] }}" autofocus required />
        <x-forms.inputs.field wire:model="contact_name" wire:keydown.enter="save" name="contact_name" label="{{ __('timer.contact_name') }}" value="{{ $client_to_edit['contact_name'] }}" />
        <x-forms.inputs.field wire:model="contact_email" wire:keydown.enter="save" name="contact_email" label="{{ __('timer.contact_email') }}" value="{{ $client_to_edit['contact_email'] }}" />
        <x-forms.inputs.field wire:model="contact_phone" wire:keydown.enter="save" name="contact_phone" label="{{ __('timer.contact_phone') }}" value="{{ $client_to_edit['contact_phone'] }}" />

        <div class="mt-5 flex justify-between">
            <x-buttons.secondary wire:click="$dispatch('closeModal')">{{ __('timer.cancel') }}</x-buttons.secondary>
            <x-buttons.primary wire:click="save">{{ __('timer.save') }}</x-buttons.primary>
        </div>

    </x-modal-child>
</div>
