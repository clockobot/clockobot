<div>
    <x-modal-child>
        <x-slot:title>
            {{ __("timer.edit") . ' ' . $work_type_to_edit->title }}
        </x-slot:title>

        <x-forms.inputs.field wire:model="title" name="title" label="{{ __('timer.title') }}" />

        <div class="mt-5 flex justify-between">
            <x-buttons.secondary wire:click="$dispatch('closeModal')">{{ __('timer.cancel') }}</x-buttons.secondary>
            <x-buttons.primary wire:click="save">{{ __('timer.save') }}</x-buttons.primary>
        </div>
    </x-modal-child>
</div>
