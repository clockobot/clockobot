<th scope="col"
    {{ $attributes
        ->class([
            'px-4 py-3.5 text-left text-xs lg:text-sm font-semibold',
            'relative py-3.5' => $attributes->has('last'),
        ])
        ->merge([
            'class' => $attributes->get('class'),
        ])
    }}
>
    {{ $slot }}

    @if($attributes->has('last'))
        <span class="sr-only">Éditer</span>
    @endif
</th>
