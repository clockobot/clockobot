<div>
    <x-modal-child wire:key="add-new-entry">
        <x-slot:title>
            <h4 class="font-bold flex-1">{{ __('timer.add_client') }}</h4>
        </x-slot:title>

        <div>
            <x-forms.inputs.field wire:model="name" wire:keydown.enter="addClient" name="name" label="{{ __('timer.client_name') }}" value="{{ old('name') }}" autofocus />
            <x-forms.inputs.field wire:model="contact_name" wire:keydown.enter="addClient" name="contact_name" label="{{ __('timer.contact_name') }}" value="{{ old('contact_name') }}" />
            <x-forms.inputs.field wire:model="contact_email" wire:keydown.enter="addClient" name="contact_email" label="{{ __('timer.contact_email') }}" value="{{ old('contact_email') }}" />
            <x-forms.inputs.field wire:model="contact_phone" wire:keydown.enter="addClient" name="contact_phone" label="{{ __('timer.contact_phone') }}" value="{{ old('contact_phone') }}" />

            <div class="mt-5 flex justify-between">
                <x-buttons.secondary type="button" wire:click="$dispatch('closeModal')">{{ __('timer.cancel') }}</x-buttons.secondary>
                <x-buttons.primary wire:click="addClient">{{ __('timer.save') }}</x-buttons.primary>
            </div>
        </div>
    </x-modal-child>
</div>
