<div {{ $attributes->merge(['class' => 'flex text-day-7 max-w-lg']) }}>
    <div class="flex flex-1 space-x-4 items-center">
        <input id="{{ $inputId ?? 'input-id' }}"
               name="{{ $inputName ?? 'input-name' }}"
               placeholder="{{ $placeholder }}"
               wire:keydown.enter="{{ $enterAction }}"
               wire:model="{{ $model }}"
               class="min-w-[200px] relative z-0 bg-white dark:bg-night-5 text-day-7 dark:text-night-1 placeholder:italic placeholder:text-day-5 dark:placeholder:text-night-3 rounded-l border border-r-0 border-day-3 dark:border-night-4 w-full py-1.5 px-2 focus:ring-1 focus:outline-none focus:ring-day-4 focus:border-day-4 dark:focus:ring-night-4 dark:focus:border-night-4">
    </div>
    <x-buttons.primary wire:click="{{ $buttonAction }}" class="rounded-none rounded-r">
        {{ $buttonText }}
    </x-buttons.primary>
</div>
