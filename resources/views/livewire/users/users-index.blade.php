<div>
    <div class="page-layout">
        <div class="page-wrapper">
            <x-pages.header page_title="{{ __('timer.users') }}" page_intro="">
                <x-slot:page_actions>
                    <x-pages.search-item
                        inputId="query"
                        inputName="query"
                        placeholder="{{ __('timer.search_user') }}"
                        enterAction="search"
                        changeAction="search"
                        model="query"
                    />

                    <x-buttons.primary type="button" onclick="Livewire.dispatch('openModal', { component: 'modals.users.add-user-modal' })">
                        {{ __('timer.add_user') }}
                    </x-buttons.primary>
                </x-slot>
            </x-pages.header>

            <x-tables.table>
                <x-slot:headers>
                    <x-tables.th first class="hidden md:table-cell"><span class="sr-only">Avatar</span></x-tables.th>
                    <x-tables.th>{{ __('timer.name') }}</x-tables.th>
                    <x-tables.th class="hidden lg:table-cell">{{ __('timer.email') }}</x-tables.th>
                    <x-tables.th last></x-tables.th>
                </x-slot:headers>

                <x-slot:body>
                    @forelse($this->users as $user)
                        <x-tables.tr>
                            <x-tables.td first class="hidden md:table-cell">
                                <div>
                                    <x-tables.table-avatar :user="$user" />
                                </div>
                            </x-tables.td>
                            <x-tables.td>
                                {{ $user->name }}
                                <span class="block lg:hidden text-day-6 dark:text-night-1 mt-0.5 break-all">{{ $user->email }}</span>
                            </x-tables.td>
                            <x-tables.td class="hidden lg:table-cell break-all">
                                {{ $user->email }}
                            </x-tables.td>
                            <x-tables.td last>
                                <x-buttons.icons.edit wire:key="{{ 'edit-' . $user->id }}" onclick="Livewire.dispatch('openModal', {component: 'modals.users.edit-user-modal', arguments: {id: {{ $user->id }} } })"></x-buttons.icons.edit>
                                <x-buttons.icons.delete wire:key="{{ 'delete-' . $user->id }}" onclick="Livewire.dispatch('openModal', {component: 'modals.users.delete-user-modal', arguments: {id: {{ $user->id}} } })"></x-buttons.icons.delete>
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

            {{ $this->users->links() }}
        </div>
    </div>
</div>
