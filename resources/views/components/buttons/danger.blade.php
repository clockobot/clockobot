@if(($attributes['type'] == 'link'))
    <a {{ $attributes->class([
        'py-2.5 text-base' => isset($size) && $size == 'lg',
        'py-1 text-xs' => isset($size) && $size == 'xs'
    ])->merge([
        'class' => 'cursor-pointer inline-flex items-center px-4 py-2 rounded shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition-colors focus:outline-none focus:ring-0 transition-colors ease-in-out duration-150'
    ]) }}>
        {{ $slot }}
    </a>
@else
    <button {!! (!isset($type) ? 'type="submit"' : '') !!} {{ $attributes->class([
        'py-2.5 text-base' => isset($size) && $size == 'lg',
        'py-1 text-xs' => isset($size) && $size == 'xs'
    ])->merge([
        'class' => 'cursor-pointer inline-flex items-center px-4 py-2 rounded shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition-colors focus:outline-none focus:ring-0 transition-colors ease-in-out duration-150'
    ]) }}>
        {{ $slot }}
    </button>
@endif
