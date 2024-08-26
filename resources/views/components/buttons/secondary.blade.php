@if(($attributes['type'] == 'link'))
    <a {{ $attributes->class([
        'py-2.5 text-base' => isset($size) && $size == 'lg',
        'py-1 text-xs' => isset($size) && $size == 'xs'
    ])->merge([
        'class' => 'cursor-pointer flex items-center px-4 py-2 rounded shadow-sm text-sm font-bold text-white bg-day-5 hover:bg-day-6 dark:bg-night-4 dark:hover:bg-night-5 transition-colors focus:outline-none focus:ring-0'
    ]) }}>
        {{ $slot }}
    </a>
@else
    <button {!! (!isset($type) ? 'type="submit"' : '') !!} {{ $attributes->class([
        'py-2.5 text-base' => isset($size) && $size == 'lg',
        'py-1 text-xs' => isset($size) && $size == 'xs'
    ])->merge([
        'class' => 'cursor-pointer flex items-center px-4 py-2 rounded shadow-sm text-sm font-bold text-white bg-day-5 hover:bg-day-6 dark:bg-night-4 dark:hover:bg-night-5 transition-colors focus:outline-none focus:ring-0'
    ]) }}>
        {{ $slot }}
    </button>
@endif
