<td
    {{ $attributes
        ->class([
            'px-4 py-4 text-xs lg:text-sm',
            'relative py-4 text-right text-sm font-medium align-middle' => $attributes->has('last'),
            'font-medium text-app' => $attributes->has('first'),
        ])
        ->merge([
            'class' => $attributes->get('class'),
        ])
    }}
>
    @if($attributes->has('last'))<div class="flex flex-col space-y-2 md:flex-row md:items-center md:justify-end md:space-y-0 md:space-x-2">@endif
        {{ $slot }}
        @if($attributes->has('last'))</div>@endif
</td>
