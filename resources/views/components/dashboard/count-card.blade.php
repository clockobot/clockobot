<div class="overflow-hidden rounded-lg bg-day-1 dark:bg-night-5 py-2 px-4 shadow md:px-6 text-center lg:py-6 lg:flex lg:items-center lg:justify-between">
    <dt class="truncate text-base font-medium text-app">{{ $card_title }}</dt>
    <dd class="mt-1 lg:mt-0 text-2xl font-semibold tracking-tight text-day-7 dark:text-night-1 flex justify-center items-center space-x-2">
        <span>{{ number_format($count, 2) }}</span>
        <span class="text-day-6 dark:text-night-3 font-light text-xl">{{ __('timer.hours') }}</span>
    </dd>
</div>
