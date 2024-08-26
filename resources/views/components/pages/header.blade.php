@props([
    'page_title',
    'page_intro',
    'back_link' => null,
])

<div class="flex flex-col space-y-4 lg:space-y-0 lg:flex-row lg:justify-between lg:items-center">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            @isset($back_link)
                <div class="flex justify-start items-center space-x-4">
                    <a href="{{ $back_link }}" wire:navigate class="h-6 w-6 text-app" title="back">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                    </a>
            @endisset

            <h1 class="text-xl font-semibold">
                {{ $page_title }}
            </h1>

            @isset($back_link)
                </div>
            @endisset
            <p class="mt-2 text-sm whitespace-normal">{{ $page_intro }}</p>
        </div>
    </div>

    <div class="flex flex-col justify-start items-start space-y-4 lg:space-y-0 lg:flex-row lg:space-x-4">
        {{ $page_actions }}
    </div>
</div>
