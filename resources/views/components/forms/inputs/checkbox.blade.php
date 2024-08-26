@props([
    'errors' => false,
    'name' => '',
    'label' => '',
    'legend' => '',
    'class' => '',
    'checked' => false, // Default to false
])

<div class="mt-2">
    <div>
        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
            <input type="hidden" name="{{ $name }}" value="0">
            <input type="checkbox" name="{{ $name }}" wire:model="{{ $name }}" id="{{ $name }}" value="1" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white appearance-none cursor-pointer" {{ $checked ? 'checked' : '' }} />
            <label for="{{ $name }}" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
        </div>

        @isset($legend)
            <label for="{{ $legend }}" class="text-xs text-gray-700">{!! $legend !!}</label>
        @endisset
    </div>
</div>
