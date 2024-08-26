<div>
    <x-modal-child>
        <x-slot:title>
            <h4 class="font-bold flex-1">
                {{ __('timer.add_time_entries') }}
            </h4>
            <div class="shrink-0 font-normal">
                <div class="flex space-x-2 items-center">
                    <label class="text-sm text-day-7 dark:text-white inline-block" for="billable">{{ __('timer.billable') . '?' }}</label>
                    <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                        <input type="checkbox" name="billable" wire:model="billable" id="billable" value="1" class="toggle-checkbox absolute block -mt-0.5 w-7 h-7 rounded-full bg-white appearance-none cursor-pointer">
                        <label for="billable" class="toggle-label block overflow-hidden h-6 rounded-full cursor-pointer"></label>
                    </div>
                    <label for="billable" class="text-xs"></label>
                </div>
            </div>
        </x-slot:title>

        <div class="w-full flex flex-col space-y-2">
            <div class="w-full flex space-x-4">
                <div class="flex flex-col flex-1">
                    <x-forms.inputs.field type="date" wire:model="start_date" name="start_date" label="{{ __('timer.date_start') }}" parentClass="mb-2" required />
                </div>
                <div class="flex flex-col flex-1">
                    <x-forms.inputs.field type="time" wire:model="start_time" name="start_time" label="{{ __('timer.hour_start') }}" parentClass="mb-2" required />
                </div>
            </div>

            <div class="w-full flex space-x-4">
                <div class="flex flex-col flex-1">
                    <x-forms.inputs.field type="date" wire:model="end_date" name="end_date" label="{{ __('timer.date_end') }}" parentClass="mb-2" required />
                </div>
                <div class="flex flex-col flex-1">
                    <x-forms.inputs.field type="time" wire:model="end_time" name="end_time" label="{{ __('timer.hour_end') }}" parentClass="mb-2" required />
                </div>
            </div>

        </div>

        <div class="grid grid-cols-3 gap-x-4">
            <x-forms.inputs.field wire:model="client_id" name="client_id" wire:change="$refresh" type="select" label="{{ __('timer.client') }}" required>
                <x-slot name="options">
                    <option value=""> -- {{ __('timer.choose') }} -- </option>
                    @foreach($this->clients() as $client)
                        <option value="{{ $client->id }}" {{ (old('client_id') == $client->id) ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </x-slot>
            </x-forms.inputs.field>

            <x-forms.inputs.field wire:model="project_id" name="project_id" type="select" label="{{ __('timer.project') }}" required>
                <x-slot name="options">
                    <option value=""> -- {{ __('timer.choose') }} -- </option>
                    @foreach($this->projects() as $project)
                        <option value="{{ $project->id }}" {{ (old('project_id') == $project->id) ? 'selected' : '' }}>{{ $project->title }}</option>
                    @endforeach
                </x-slot>
            </x-forms.inputs.field>

            <x-forms.inputs.field wire:model="work_type_id" name="work_type_id" type="select" label="{{ __('timer.work_type') }}" required>
                <x-slot name="options">
                    <option value=""> -- {{ __('timer.choose') }} -- </option>
                    @foreach($this->work_types() as $work_type)
                        <option value="{{ $work_type->id }}" {{ (old('work_type_id') == $work_type->id) ? 'selected' : '' }}>{{ $work_type->title }}</option>
                    @endforeach
                </x-slot>
            </x-forms.inputs.field>
        </div>

        <x-forms.inputs.field wire:model="description" name="description" type="textarea" label="{{ __('timer.description') }}">
        </x-forms.inputs.field>

        <x-forms.inputs.field wire:model="link" name="link" type="url" label="{{ __('timer.ticket_link') }}" placeholder="https://">
        </x-forms.inputs.field>

        <div class="mt-5 flex justify-between">
            <x-buttons.secondary type="button" wire:click="$dispatch('closeModal')">{{ __('timer.cancel') }}</x-buttons.secondary>
            <div class="flex space-x-5">
                <x-buttons.secondary type="button" wire:click="deleteTimeEntry">{{ __('timer.stop') }}</x-buttons.secondary>
                <x-buttons.primary type="button" wire:click="addTimeEntry">{{ __('timer.save') }}</x-buttons.primary>
            </div>
        </div>

    </x-modal-child>
</div>
