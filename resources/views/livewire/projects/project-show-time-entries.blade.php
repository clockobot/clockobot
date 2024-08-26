<div>
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
            @foreach($project->time_entries as $time_entry)
                <x-tables.tr>
                    <x-tables.td class="hidden lg:table-cell" first>
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
                        {{ (!empty($time_entry->description)) ? ((strlen($time_entry->description) > 120) ? substr($time_entry->description, 0, 120) . '...' : $time_entry->description) : __('timer.no_description') }}
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

            @if($project->time_entries === 0)
                <x-tables.no-results />
            @endif
        </x-slot:body>

    </x-tables.table>
</div>
