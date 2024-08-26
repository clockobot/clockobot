<div>
    <div class="page-layout">
        <div class="page-wrapper">
            <x-pages.header page_title="{{ __('timer.projects') }}" page_intro="{{ __('timer.projects_intro') }}">
                <x-slot:page_actions>
                    <x-pages.search-item
                        inputId="query"
                        inputName="query"
                        placeholder="{{ __('timer.search_project') }}"
                        enterAction="search"
                        changeAction="search"
                        model="query"
                    />

                    <x-buttons.primary type="button" onclick="Livewire.dispatch('openModal', { component: 'modals.projects.add-project-modal' })">
                        {{ __('timer.add_project') }}
                    </x-buttons.primary>
                </x-slot>
            </x-pages.header>

            <x-tables.table>
                <x-slot:headers>
                    <x-tables.th first>{{ __('timer.project') }}</x-tables.th>
                    <x-tables.th class="hidden lg:table-cell">{{ __('timer.client') }}</x-tables.th>
                    <x-tables.th class="hidden lg:table-cell">{{ __('timer.description') }}</x-tables.th>
                    <x-tables.th class="hidden lg:table-cell">{{ __('timer.status') }}</x-tables.th>
                    <x-tables.th class="hidden lg:table-cell">{{ __('timer.deadline') }}</x-tables.th>
                    <x-tables.th last></x-tables.th>
                </x-slot:headers>

                <x-slot:body>
                    @forelse($this->projects() as $project)
                        <x-tables.tr>
                            <x-tables.td first>
                                <a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a>
                                <span class="block lg:hidden text-day-6 dark:text-night-1 mt-0.5">{{ $project->client->name }}</span>
                                @if($project->hour_estimate)
                                    <div class="block lg:hidden relative border border-app-contrast w-[120px] h-8 rounded mt-4">

                                        @if($project->hours_consumption() > 100)
                                            <span style="width: 0; transition: width 1s ease;"
                                                  class="w-full transition-all duration-700 ease-in-out block h-full bg-app rounded text-white"
                                                  :style="'width: 100%'">
                                        @elseif($project->hours_consumption() > 50 && $project->hours_consumption() < 100)
                                                    <span style="width: 0; transition: width 1s ease;"
                                                          class="transition-all duration-700 ease-in-out block h-full bg-app/50 rounded text-app-contrast"
                                                          :style="'width: ' + {{ $project->hours_consumption() }} + '%'">
                                        @else
                                                            <span style="width: 0; transition: width 1s ease;"
                                                                  class="transition-all duration-700 ease-in-out block h-full bg-app/20 rounded text-app-contrast"
                                                                  :style="'width: ' + {{ $project->hours_consumption() }} + '%'">
                                        @endif
                                                <i class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 font-semibold">{{ number_format($project->hours_consumption(), 2) . '%' }}</i>
                                            </span>
                                    </div>
                                @else
                                    <div class="block lg:hidden relative flex justify-center items-center border border-app-contrast text-app-contrast w-[120px] h-8 rounded italic font-medium mt-4">
                                        <span>{{ __('timer.non_estimated') }}</span>
                                    </div>
                                @endif
                            </x-tables.td>
                            <x-tables.td class="hidden lg:table-cell">{{ $project->client->name }}</x-tables.td>
                            <x-tables.td class="hidden lg:table-cell">{{ $project->description }}</x-tables.td>
                            <x-tables.td class="hidden lg:table-cell">
                                @if($project->hour_estimate)
                                    <div class="relative border border-app-contrast w-[120px] h-8 rounded">

                                        @if($project->hours_consumption() > 100)
                                            <span style="width: 0; transition: width 1s ease;"
                                                  class="w-full transition-all duration-700 ease-in-out block h-full bg-app rounded text-white"
                                                  :style="'width: 100%'">
                                        @elseif($project->hours_consumption() > 50 && $project->hours_consumption() < 100)
                                            <span style="width: 0; transition: width 1s ease;"
                                                    class="transition-all duration-700 ease-in-out block h-full bg-app/50 rounded text-app-contrast"
                                                    :style="'width: ' + {{ $project->hours_consumption() }} + '%'">
                                        @else
                                            <span style="width: 0; transition: width 1s ease;"
                                                  class="transition-all duration-700 ease-in-out block h-full bg-app/20 rounded text-app-contrast"
                                                  :style="'width: ' + {{ $project->hours_consumption() }} + '%'">
                                        @endif
                                                <i class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 font-semibold">{{ number_format($project->hours_consumption(), 2) . '%' }}</i>
                                            </span>
                                    </div>
                                @else
                                    <div class="relative flex justify-center items-center border border-app-contrast text-app-contrast w-[120px] h-8 rounded italic font-medium">
                                        <span>{{ __('timer.non_estimated') }}</span>
                                    </div>
                                @endif
                            </x-tables.td>

                            <x-tables.td class="hidden lg:table-cell">{{ \Carbon\Carbon::parse($project->deadline)->format('d.m.Y') }}</x-tables.td>

                            <x-tables.td last>
                                <x-buttons.icons.show wire:navigate wire:key="{{ 'show-' . $project->id }}" href="{{ route('projects.show', $project->id) }}"></x-buttons.icons.show>
                                <x-buttons.icons.edit wire:key="{{ 'edit-' . $project->id }}" onclick="Livewire.dispatch('openModal', {component: 'modals.projects.edit-project-modal', arguments: {id: {{ $project->id }} } })"></x-buttons.icons.edit>
                                <x-buttons.icons.delete wire:key="{{ 'delete-' . $project->id }}" onclick="Livewire.dispatch('openModal', {component: 'modals.projects.delete-project-modal', arguments: {id: {{ $project->id }} } })"></x-buttons.icons.delete>
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

            {{ $this->projects()->links() }}
        </div>
    </div>
</div>
