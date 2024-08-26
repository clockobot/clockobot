<div>
    <div class="mb-8">
        <h3 class="text-day-7 dark:text-night-4 font-medium text-xl">{{ __('timer.quick_links') }}</h3>

        <ul role="list" class="mt-3 grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
            @forelse($latest_work as $entry)
                <li class="col-span-1 flex rounded-md shadow">
                    <div class="flex flex-1 items-center justify-between rounded-l-md bg-day-1 dark:bg-night-5">
                        <div class="flex-1 px-4 py-2 text-xs md:text-sm font-medium">
                            <p class="text-day-6 dark:text-night-3 break-words whitespace-normal">{{ $entry->client->name }}</p>
                            <p class="text-day-7 dark:text-night-2 break-words whitespace-normal">{{ $entry->project->title }}</p>
                            <p class="text-app">{{ $entry->work_type->title }}</p>
                        </div>
                    </div>
                    <div class="flex w-16 flex-shrink-0 items-center justify-center text-white bg-app hover:bg-app-contrast cursor-pointer rounded-r-md text-sm font-medium transition-colors group" onclick="Livewire.dispatch('startAutoTimer', {'client_id': {{ $entry->client->id }}, 'project_id': {{ $entry->project->id }}, 'work_type_id': {{ $entry->work_type->id }} })">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 transition-all duration-75 group-hover:scale-125">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                        </svg>
                    </div>
                </li>
            @empty
                <div>
                    {{ __('timer.nothing_to_show') }}
                </div>
            @endforelse
        </ul>
    </div>
</div>
