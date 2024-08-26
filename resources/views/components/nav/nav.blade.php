@persist('nav')
    <div x-data="{
            init() {
                if (localStorage.getItem('sidebar-visible') === null) {
                    localStorage.setItem('sidebar-visible', 'false')
                }
                if (localStorage.getItem('theme') === 'dark') {
                    document.body.classList.add('dark');
                } else {
                    document.body.classList.remove('dark');
                }
            },
            toggleDarkMode() {
                document.body.classList.toggle('dark');
                if (document.body.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }
            }

        }" class="sticky top-0">

        <div x-data="{ open: false, sidebarOpen: localStorage.getItem('sidebar-visible') === 'true' }"
             x-init="$watch('sidebarOpen', val => localStorage.setItem('sidebar-visible', val))"
             @keydown.window.escape="open = true"
             class="h-screen overflow-x-hidden">

            <div class="relative z-10 h-screen overflow-y-auto transition-all duration-300 flex flex-col flex-shrink-0 overflow-x-hidden" :class="{ 'w-16': !sidebarOpen, 'w-64': sidebarOpen }" x-cloak>
                <div class="relative flex grow flex-col gap-y-5 overflow-y-auto bg-day-2 dark:bg-night-8 px-6 pb-4 overflow-x-hidden">

                    <x-nav.nav-logo />

                    <div class="absolute top-0 right-0 w-8 h-16 dark:bg-transparent hover:bg-day-3 dark:hover:bg-night-6 text-day-7 dark:text-night-1 flex justify-center items-center px-8 cursor-pointer transition-colors" @click="sidebarOpen = !sidebarOpen">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 shrink-0 transform rotate-0 transition-transform" :class="{ 'rotate-180': !sidebarOpen }" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                        </svg>
                    </div>

                    <nav class="flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col gap-y-7">

                            <x-nav.theme-trigger />

                            <li x-cloak>
                                <ul role="list" class="-mx-2 space-y-1" :class="{ '-mx-3': !sidebarOpen }" x-cloak>
                                    <x-nav.nav-item href="{{ route('dashboard') }}" :is-active="request()->routeIs('dashboard')" label="{{ __('timer.dashboard') }}">
                                        <svg class="h-6 w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                        </svg>
                                    </x-nav.nav-item>

                                    <x-nav.nav-item href="{{ route('time_entries.index') }}" :is-active="request()->routeIs('time_entries.index')" label="{{ __('timer.time_entries') }}">
                                        <svg class="h-6 w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </x-nav.nav-item>

                                    <x-nav.nav-item href="{{ route('clients.index') }}" :is-active="request()->routeIs('clients.index')" label="{{ __('timer.clients') }}">
                                        <svg class="h-6 w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                        </svg>
                                    </x-nav.nav-item>

                                    <x-nav.nav-item href="{{ route('projects.index') }}" :is-active="request()->routeIs('projects.*')" label="{{ __('timer.projects') }}">
                                        <svg class="h-6 w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
                                        </svg>
                                    </x-nav.nav-item>

                                    <x-nav.nav-item href="{{ route('work_types.index') }}" :is-active="request()->routeIs('work_types.index')" label="{{ __('timer.work_types') }}">
                                        <svg class="w-6 h-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                                        </svg>
                                    </x-nav.nav-item>

                                    <x-nav.nav-item href="{{ route('reporting.index') }}" :is-active="request()->routeIs('reporting.index')" label="{{ __('timer.reporting') }}">
                                        <svg class="h-6 w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
                                        </svg>
                                    </x-nav.nav-item>

                                    <x-nav.nav-item href="{{ route('settings.index') }}" :is-active="request()->routeIs('settings.index')" label="{{ __('timer.settings') }}">
                                        <svg class="h-6 w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </x-nav.nav-item>

                                    @if(Auth::user()->is_admin)
                                        <x-nav.nav-item href="{{ route('users.index') }}" :is-active="request()->routeIs('users.*')" label="{{ __('timer.users') }}">
                                            <svg class="h-6 w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                            </svg>
                                        </x-nav.nav-item>
                                    @endif
                                </ul>
                            </li>

                            <li class="-mx-2 mt-auto" :class="{ '-mx-3': !sidebarOpen }" x-cloak>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full appearance-none nav-el group">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                        </svg>
                                        <span x-cloak :class="{ 'hidden': !sidebarOpen }" class="whitespace-nowrap">{{ __('timer.logout') }}</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endpersist
