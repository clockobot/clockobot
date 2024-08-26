<div>
    <div class="flex flex-col md:flex-row justify-between">
        <h3 class="text-day-7 dark:text-night-4 font-medium text-xl mb-4 md:mb-0">{{ __('timer.recent_activities') }}</h3>

        <x-buttons.primary wire:navigate type="link" href="{{ route('time_entries.index') }}" class="shrink-0 inline">
            {{ __('timer.recent_activities_see_all') }}
        </x-buttons.primary>
    </div>

    <ul role="list" class="divide-y divide-day-3 dark:divide-night-4 border-t border-day-3 dark:border-night-4 mt-5">
        @foreach($last_five_entries as $time_entry)
            <li class="w-full flex flex-col justify-start items-start lg:flex-row lg:justify-between lg:items-stretch lg:gap-x-6 py-4 px-4 even:bg-day-1 dark:even:bg-night-8 hover:bg-day-2 dark:hover:bg-night-5">
                <div class="flex flex-col md:flex-row lg:w-2/12 min-w-0 gap-x-4 mb-2 lg:mb-0">
                    <div>
                        <div class="h-10 w-10 md:h-16 md:w-16 overflow-hidden rounded-full text-day-6 dark:text-night-1 bg-day-1 dark:bg-night-6 border border-day-4 dark:border-night-2 flex justify-center items-center text-xs shadow mb-2 md:mb-0">
                            @if($time_entry->user->avatar)
                                <img src="{{ Storage::url($time_entry->user->avatar) }}" class="w-full h-full object-cover" alt="{{ $time_entry->user->name . ' - ' }}">
                            @else
                                <img src="{{ asset('images/clockobot-avatar.jpg') }}" class="w-full h-full object-cover" alt="{{ $time_entry->user->name . ' - ' }}">
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col justify-center items-start min-w-0">
                    <span class="block bg-white dark:bg-night-7 text-day-6 dark:text-night-1 rounded-full px-4 py-1 text-xs mb-2 inline-flex border border-day-4 dark:border-night-5 break-words whitespace-normal">
                        {{ $time_entry->client->name }}
                    </span>
                        <p class="text-sm font-medium">
                            <span class="text-day-6 dark:text-night-3">{{ $time_entry->project->title }}</span>
                        </p>
                    </div>
                </div>

                <div class="w-full md:flex-1 text-left md:min-w-0 md:gap-x-4 text-xs md:text-sm text-day-7 dark:text-night-1 mb-4 md:md:0">
                    @if(!empty($time_entry->description))
                        <div class="break-all whitespace-normal">
                            {{ $time_entry->description }}
                        </div>
                    @else
                        {{ __('timer.no_description') }}
                    @endif

                    @if(!empty($time_entry->link))
                        <p class="font-light italic mt-5">
                            {!! '<strong>' . __('timer.link') . '</strong>' . ': <a href="'. $time_entry->link .'" target="_blank" class="hover:text-day-6 dark:hover:text-night-2 underline-offset-2 underline break-all whitespace-normal">' .$time_entry->link . '</a>' !!}
                        </p>
                    @endif
                </div>

                <div class="flex flex-col md:flex-row md:justify-start md:items-stretch">
                    <div class="hidden md:flex justify-start items-center text-2xl border-r mr-4 pr-4 font-semibold text-day-7 dark:text-night-1">
                        {{ $time_entry->calculateDurationInHours() }}
                    </div>
                    <div class="md:w-[300px] flex flex-grow-0 sm:flex flex-col items-start justify-center text-xs md:text-sm">
                        <p class="w-full flex">
                            <span class="sm:flex-1 text-app font-medium mr-2">{{ __('timer.start') }}</span>
                            <time class="sm:flex-1 text-right text-day-7 dark:text-night-1" datetime="2023-01-23T13:23Z">{{ $time_entry->start->format('d.m.Y à H:i') }}</time>
                        </p>
                        <p class="w-full flex">
                            <span class="sm:flex-1 text-app font-medium mr-2">{{ __('timer.end') }}</span>
                            <time class="sm:flex-1 text-right text-day-7 dark:text-night-1" datetime="2023-01-23T13:23Z">{{ $time_entry->end->format('d.m.Y à H:i') }}</time>
                        </p>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
