<div>
    <x-modal-child>
        <x-slot:title>
            {{ __("timer.edit") . ' ' . $project_to_edit->name }}
        </x-slot:title>

        <x-forms.inputs.field wire:model="client_id" name="client_id" type="select" label="Client" required autofocus>
            <x-slot name="options">
                <option selected="" value=""> -- {{ __('timer.choose') }} -- </option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ ($project_to_edit->client_id == $client->id) ? 'selected' : '' }}>{{ $client->name }}</option>
                @endforeach
            </x-slot>
        </x-forms.inputs.field>

        <x-forms.inputs.field wire:model="title" wire:keydown.enter="save" name="title" label="{{ __('timer.project_title') }}" />
        <x-forms.inputs.field wire:model="description" wire:keydown.enter="save" name="description" label="{{ __('timer.description') }}" />
        <x-forms.inputs.field type="date" wire:model="deadline" wire:keydown.enter="save" name="deadline" label="{{ __('timer.deadline') }}" />
        <x-forms.inputs.field type="number" wire:model="hour_estimate" wire:keydown.enter="save" name="hour_estimate" label="{{ __('timer.time_estimate') }}" />

        <div class="mt-5 flex justify-between">
            <x-buttons.secondary wire:click="$dispatch('closeModal')">{{ __('timer.cancel') }}</x-buttons.secondary>
            <x-buttons.primary wire:click="save">{{ __('timer.save') }}</x-buttons.primary>
        </div>

    </x-modal-child>
</div>
