<div {{ $attributes->merge(['class' => 'flex min-w-[280px]']) }}>
    <div class="relative w-full flex items-center">
        <input id="{{ $inputId ?? 'query' }}"
               placeholder="{{ $placeholder }}"
               name="{{ $inputName ?? 'query' }}"
               value=""
               wire:keydown.enter="{{ $enterAction }}"
               wire:change="{{ $changeAction }}"
               wire:model.live="{{ $model }}"
               class="min-w-[280px] relative z-0 bg-white dark:bg-night-5 text-day-7 dark:text-night-1 placeholder:italic placeholder:text-day-5 dark:placeholder:text-night-3 rounded border border-day-3 dark:border-night-4 w-full py-1.5 px-2 focus:ring-1 focus:outline-none focus:ring-day-4 focus:border-day-4 dark:focus:ring-night-4 dark:focus:border-night-4">

        <div
            class="absolute flex items-center justify-center z-1 w-10 h-full bg-day-1 dark:bg-app border border-day-3 hover:bg-day-2 dark:border-0 dark:hover:bg-app-contrast dark:text-white transition-colors cursor-pointer top-0 right-0 rounded-r"
            onclick="(function(el) { el.value = ''; el.dispatchEvent(new Event('input', { bubbles: true })); el.dispatchEvent(new Event('change', { bubbles: true })); })(document.getElementById('{{ $inputId ?? 'query' }}'));"
        >

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
    </div>
</div>
