<div>
    <x-modal-child wire:key="add-new-entry">
        <x-slot:title>
            <h4 class="font-bold flex-1">{{ __('timer.add_user') }}</h4>
        </x-slot:title>

        <div>
            <x-forms.inputs.field wire:model="name" wire:keydown.enter="addUser" name="name" label="{{ __('timer.user_name') }}" value="{{ old('name') }}" autofocus />
            <x-forms.inputs.field wire:model="email" wire:keydown.enter="addUser" name="email" label="{{ __('timer.user_email') }}" value="{{ old('email') }}" />
            <x-forms.inputs.field wire:model="locale" name="locale" type="select" label="{{ __('timer.language') }}">
                <x-slot name="options">
                    <option value=""> -- {{ __('timer.choose') }} -- </option>
                    <option value="en">{{ __('timer.english') }}</option>
                    <option value="fr">{{ __('timer.french') }}</option>
                    <option value="de">{{ __('timer.german') }}</option>
                    <option value="es">{{ __('timer.spanish') }}</option>
                </x-slot>
            </x-forms.inputs.field>
            <x-forms.inputs.field type="checkbox" wire:model="is_admin" name="is_admin" label="{{ __('timer.is_admin') }}" :checked="0"></x-forms.inputs.field>

            <div class="mt-5 flex justify-between">
                <x-buttons.secondary type="button" wire:click="$dispatch('closeModal')">{{ __('timer.cancel') }}</x-buttons.secondary>
                <x-buttons.primary wire:click="addUser">{{ __('timer.save') }}</x-buttons.primary>
            </div>
        </div>
    </x-modal-child>
</div>
