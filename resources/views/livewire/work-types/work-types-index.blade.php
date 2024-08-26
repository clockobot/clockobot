<div>
    <div class="page-layout">
        <div class="page-wrapper">
            <x-pages.header page_title="{{ __('timer.work_types') }}" page_intro="{{ __('timer.work_types_intro') }}">
                <x-slot:page_actions>
                    <x-pages.add-item
                        inputId="title"
                        inputName="title"
                        placeholder="{{ __('timer.work_type_title') }}"
                        enterAction="addWorkType"
                        model="work_type_title"
                        buttonAction="addWorkType"
                        buttonText="{{ __('timer.add') }}"
                    />

                    <x-pages.search-item
                        inputId="query"
                        inputName="query"
                        placeholder="{{ __('timer.search_work_type') }}"
                        enterAction="search"
                        changeAction="search"
                        model="query"
                    />
                </x-slot>
            </x-pages.header>

            @error('work_type_title') <p class="mt-1 text-sm text-center text-red-400 italic">{{ $message }}</p> @enderror

            <x-tables.table>
                <x-slot:headers>
                    <x-tables.th first>{{ __('timer.title') }}</x-tables.th>
                    <x-tables.th last></x-tables.th>
                </x-slot:headers>

                <x-slot:body>
                    @forelse($this->work_types() as $work_type)
                        <x-tables.tr>
                            <x-tables.td first>{{ $work_type->title }}</x-tables.td>

                            <x-tables.td last>
                                <x-buttons.icons.edit wire:key="{{ 'edit-' . $work_type->id }}" onclick="Livewire.dispatch('openModal', {component: 'modals.work-types.edit-work_type-modal', arguments: {id: {{ $work_type->id }} } })"></x-buttons.icons.edit>
                                <x-buttons.icons.delete wire:key="{{ 'delete-' . $work_type->id }}" onclick="Livewire.dispatch('openModal', {component: 'modals.work-types.delete-work_type-modal', arguments: {id: {{$work_type->id}} } })"></x-buttons.icons.delete>
                            </x-tables.td>
                        </x-tables.tr>
                    @empty
                        {{-- div needed so "forelse" works correctly--}}
                        <div>
                            <x-tables.no-results />
                        </div>
                    @endforelse
                </x-slot:body>
            </x-tables.table>

            {{ $this->work_types()->links() }}
        </div>
    </div>
</div>
