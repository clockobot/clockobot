<div>
    <div x-data="{ active: true }" class="mx-auto w-full min-h-[1rem]">
        <div role="region">
            <div>
                <h2 x-on:click="active = !active">
                    <div :aria-expanded="active" class="flex w-full items-center justify-between px-6 py-4 text-xl font-bold text-day-7 dark:text-night-1 cursor-pointer">
                        <span>Filtres</span>
                        <span x-show="active" aria-hidden="true" class="ml-4 text-2xl flex items-center space-x-2">
                            <span class="text-sm">{{ __('timer.hide') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </span>
                        <span x-show="!active" aria-hidden="true" class="ml-4 text-2xl flex items-center space-x-2">
                            <span class="text-sm">{{ __('timer.show') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </span>
                    </div>
                </h2>

                <div x-show="active" x-collapse>
                    <div class="px-6 pb-4 text-day-7 dark:text-night-1">
                        <div class="flex flex-col lg:flex-row lg:space-x-8 lg:mb-4">
                            <div class="w-full flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                                <div class="flex flex-col flex-1">
                                    <x-forms.inputs.field type="date" wire:model="start_date" wire:change="$refresh" name="start_date" label="{{ __('timer.date_start') }}" parentClass="md:mb-2" />
                                </div>
                                <div class="flex flex-col flex-1">
                                    <x-forms.inputs.field type="time" wire:model="start_time" wire:change.debounce.1s="$refresh" name="start_time" label="{{ __('timer.hour_start') }}" parentClass="mb-2" />
                                </div>
                            </div>

                            <div class="w-full flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                                <div class="flex flex-col flex-1">
                                    <x-forms.inputs.field type="date" wire:model="end_date" wire:change="$refresh" name="end_date" label="{{ __('timer.date_end') }}" parentClass="md:mb-2" />
                                </div>
                                <div class="flex flex-col flex-1">
                                    <x-forms.inputs.field type="time" wire:model="end_time" wire:change.debounce.1s="$refresh" name="end_time" label="{{ __('timer.hour_end') }}" parentClass="mb-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:space-x-8">
                            <x-forms.inputs.field wire:model="client_id" wire:change.debounce="$refresh" name="client_id" type="select" label="{{ __('timer.client') }}">
                                <x-slot name="options">
                                    <option value=""> -- {{ __('timer.choose') }} -- </option>
                                    @foreach($this->clients() as $client)
                                        <option value="{{ $client->id }}" wire:key="{{$client->id}}">{{ $client->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-forms.inputs.field>

                            <x-forms.inputs.field wire:model="project_id" wire:change.debounce="$refresh" name="project_id" type="select" label="{{ __('timer.project') }}">
                                <x-slot name="options">
                                    <option value=""> -- {{ __('timer.choose') }} -- </option>
                                    @foreach($this->projects() as $project)
                                        <option value="{{ $project->id }}" wire:key="{{$project->id}}">{{ $project->title }}</option>
                                    @endforeach
                                </x-slot>
                            </x-forms.inputs.field>

                            <x-forms.inputs.field wire:model="user_id" wire:change.debounce="$refresh" name="user_id" type="select" label="{{ __('timer.user') }}">
                                <x-slot name="options">
                                    <option value=""> -- {{ __('timer.choose') }} -- </option>
                                    @foreach($this->users() as $user)
                                        <option value="{{ $user->id }}" wire:key="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-forms.inputs.field>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between md:items-center md:space-x-4 py-4 px-6 border-y border-day/20 dark:border-night-5">
        <div class="flex flex-1 justify-start items-center space-x-2 text-2xl font-bold text-day-7 dark:text-night-4 mb-2 md:mb-0">
            <span class="">{{ __('timer.total') . ':' }} </span>
            <span class="text-app">{{ $this->hours_total() }}</span>
            <span class="text-lg "> {{ __('timer.hours') }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <x-buttons.primary wire:click="export">{{ __('timer.export') }}</x-buttons.primary>
            <x-buttons.secondary wire:click="resetData">{{ __('timer.reset') }}</x-buttons.secondary>
        </div>
    </div>

    <div class="px-6">
        <x-tables.table>
            <x-slot:headers>
                <x-tables.th class="hidden lg:table-cell " first><span class="sr-only">{{ __('timer.user') }}</span></x-tables.th>
                <x-tables.th>{{ __('timer.client') }}</x-tables.th>
                <x-tables.th class="hidden lg:table-cell">{{ __('timer.project') }}</x-tables.th>
                <x-tables.th class="hidden lg:table-cell">{{ __('timer.work_type') }}</x-tables.th>
                <x-tables.th class="hidden lg:table-cell">{{ __('timer.description') }}</x-tables.th>
                <x-tables.th class="">{{ __('timer.duration') }}</x-tables.th>
                <x-tables.th class="hidden lg:table-cell text-center">{{ __('timer.billable') }}</x-tables.th>
                <x-tables.th last></x-tables.th>
            </x-slot:headers>

            <x-slot:body>
                @foreach($this->time_entries() as $time_entry)
                    <x-tables.tr>
                        <x-tables.td  class="hidden lg:table-cell" first>
                            <div>
                                <x-tables.table-avatar :user="$time_entry->user" />
                            </div>
                        </x-tables.td>
                        <x-tables.td>
                            <span>{{ $time_entry->client->name }}</span>
                            <span class="block mt-0.5 lg:hidden">{{ $time_entry->project->title }}</span>
                            <span class="block mt-0.5 lg:hidden">{{ $time_entry->work_type->title }}</span>
                            <span class="block mt-0.5 text-app  lg:hidden">{{ $time_entry->user->name }}</span>
                        </x-tables.td>
                        <x-tables.td class="hidden lg:table-cell">{{ $time_entry->project->title }}</x-tables.td>
                        <x-tables.td class="hidden lg:table-cell">{{ $time_entry->work_type->title }}</x-tables.td>
                        <x-tables.td class="hidden lg:table-cell lg:w-5/12">
                            {{ (!empty($time_entry->description)) ? ((strlen($time_entry->description) > 120) ? substr($time_entry->description, 0, 120) . '...' : $time_entry->description) : 'Pas de description' }}
                        </x-tables.td>
                        <x-tables.td>{{ $time_entry->calculateDurationInHours() }}</x-tables.td>
                        <x-tables.td class="hidden lg:table-cell">
                            <x-tables.billable-dot val="{{ $time_entry->billable }}"></x-tables.billable-dot>
                        </x-tables.td>

                        <x-tables.td last>
                            @if($time_entry->user->id === \Illuminate\Support\Facades\Auth::id() || Auth::user()->is_admin)
                                <x-buttons.icons.edit wire:key="{{ 'edit-' . $time_entry->id }}" onclick="Livewire.dispatch('openModal', {component: 'modals.time-entries.edit-time-entry-modal', arguments: {id: {{ $time_entry->id }} } })"></x-buttons.icons.edit>
                                <x-buttons.icons.delete wire:key="{{ 'delete-' . $time_entry->id }}" onclick="Livewire.dispatch('openModal', {component: 'modals.time-entries.delete-time-entry-modal', arguments: {id: {{$time_entry->id}} } })"></x-buttons.icons.delete>
                            @endif
                        </x-tables.td>
                    </x-tables.tr>
                @endforeach

                @if($this->time_entries()->count() === 0)
                    <x-tables.no-results />
                @endif
            </x-slot:body>

        </x-tables.table>
    </div>

</div>
