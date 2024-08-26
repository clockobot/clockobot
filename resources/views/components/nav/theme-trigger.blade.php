<li class="flex items-center space-x-3 justify-start cursor-pointer group border-y border-day-3 dark:border-night-4 py-2" :class="{ 'space-x-0': !sidebarOpen }" @click="toggleDarkMode()" id="themeSwitcher">
    <button type="button" :class="{ '-mx-1': !sidebarOpen }" class="flex justify-center items-center">
        <span class="dark:inline hidden">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 shrink-0">
                <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" class="fill-app stroke-app-contrast"></path>
                <path d="M12 4v1M17.66 6.344l-.828.828M20.005 12.004h-1M17.66 17.664l-.828-.828M12 20.01V19M6.34 17.664l.835-.836M3.995 12.004h1.01M6 6l.835.836" class="stroke-app-contrast"></path>
            </svg>
        </span>
        <span class="dark:hidden inline">
            <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6 shrink-0">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.715 15.15A6.5 6.5 0 0 1 9 6.035C6.106 6.922 4 9.645 4 12.867c0 3.94 3.153 7.136 7.042 7.136 3.101 0 5.734-2.032 6.673-4.853Z" class="fill-app"></path>
                <path d="m17.715 15.15.95.316a1 1 0 0 0-1.445-1.185l.495.869ZM9 6.035l.846.534a1 1 0 0 0-1.14-1.49L9 6.035Zm8.221 8.246a5.47 5.47 0 0 1-2.72.718v2a7.47 7.47 0 0 0 3.71-.98l-.99-1.738Zm-2.72.718A5.5 5.5 0 0 1 9 9.5H7a7.5 7.5 0 0 0 7.5 7.5v-2ZM9 9.5c0-1.079.31-2.082.845-2.93L8.153 5.5A7.47 7.47 0 0 0 7 9.5h2Zm-4 3.368C5 10.089 6.815 7.75 9.292 6.99L8.706 5.08C5.397 6.094 3 9.201 3 12.867h2Zm6.042 6.136C7.718 19.003 5 16.268 5 12.867H3c0 4.48 3.588 8.136 8.042 8.136v-2Zm5.725-4.17c-.81 2.433-3.074 4.17-5.725 4.17v2c3.552 0 6.553-2.327 7.622-5.537l-1.897-.632Z" class="fill-app"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M17 3a1 1 0 0 1 1 1 2 2 0 0 0 2 2 1 1 0 1 1 0 2 2 2 0 0 0-2 2 1 1 0 1 1-2 0 2 2 0 0 0-2-2 1 1 0 1 1 0-2 2 2 0 0 0 2-2 1 1 0 0 1 1-1Z" class="fill-app"></path>
            </svg>
        </span>
    </button>

    <div :class="{ 'hidden': !sidebarOpen }" class="whitespace-nowrap text-day-7 dark:text-night-1 text-sm leading-6 font-semibold bg-transparent p-0 block group-hover:opacity-70">
        <span class="block dark:inline hidden whitespace-nowrap">{{ __('timer.theme_light') }}</span>
        <span class="block dark:hidden inline whitespace-nowrap">{{ __('timer.theme_dark') }}</span>
    </div>
</li>
