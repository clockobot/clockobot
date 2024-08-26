<div>
    <div class="page-layout">
        <div class="page-wrapper">
            <x-pages.header page_title="{{ __('timer.client_list') }}" page_intro="{{ __('timer.client_list_intro') }}">
                <x-slot:page_actions>
                    <x-pages.search-item
                        inputId="query"
                        inputName="query"
                        placeholder="{{ __('timer.search_client') }}"
                        enterAction="search"
                        changeAction="search"
                        model="query"
                    />

                    <x-buttons.primary type="button" onclick="Livewire.dispatch('openModal', { component: 'modals.clients.add-client-modal' })">
                        {{ __('timer.add_client') }}
                    </x-buttons.primary>
                </x-slot>
            </x-pages.header>

            <x-tables.table>
                <x-slot:headers>
                    <x-tables.th first>{{ __('timer.name') }}</x-tables.th>
                    <x-tables.th class="hidden lg:table-cell">{{ __('timer.contact') }}</x-tables.th>
                    <x-tables.th class="hidden lg:table-cell">{{ __('timer.email') }}</x-tables.th>
                    <x-tables.th class="hidden lg:table-cell">{{ __('timer.phone') }}</x-tables.th>
                    <x-tables.th last></x-tables.th>
                </x-slot:headers>

                <x-slot:body>
                    @forelse($this->clients() as $client)
                        <x-tables.tr>
                            <x-tables.td first>
                                {{ $client->name }}
                                <span class="block lg:hidden text-day-6 dark:text-night-1 mt-0.5">{{ $client->contact_name }}</span>
                                <span class="block lg:hidden whitespace-normal break-words text-day-6 dark:text-night-1 mt-0.5">{{ $client->contact_email }}</span>
                                <span class="block lg:hidden whitespace-normal break-words text-day-6 dark:text-night-1 mt-0.5">{{ $client->contact_phone }}</span>
                            </x-tables.td>
                            <x-tables.td class="hidden lg:table-cell">
                                {{ $client->contact_name }}
                            </x-tables.td>
                            <x-tables.td class="hidden lg:table-cell">{{ $client->contact_email }}</x-tables.td>
                            <x-tables.td class="hidden lg:table-cell">{{ $client->contact_phone }}</x-tables.td>

                            <x-tables.td last>
                                <x-buttons.icons.edit wire:key="{{ 'edit-' . $client->id }}" onclick="Livewire.dispatch('openModal', {component: 'modals.clients.edit-client-modal', arguments: {id: {{ $client->id }} } })"></x-buttons.icons.edit>
                                <x-buttons.icons.delete wire:key="{{ 'delete-' . $client->id }}" onclick="Livewire.dispatch('openModal', {component: 'modals.clients.delete-client-modal', arguments: {id: {{ $client->id}} } })"></x-buttons.icons.delete>
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

{{--            <div wire:ignore>--}}
                {{ $this->clients()->links() }}
{{--            </div>--}}
        </div>
    </div>
</div>
