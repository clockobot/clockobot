@props([
    'errors' => false,
    'name' => '',
    'readonly' => '',
    'deactivated' => 'false',
    'label' => '',
    'class' => '',
])

<input
    type="file"
    id="{{ $name }}"
    autocorrect="off"
    autocomplete="off"
    spellcheck="false"
    autocapitalize="none"
    name="{{ $name }}"
    {!! $attributes !!}
    {!! $class !!}

    @if($deactivated == 'true')
        disabled
    @endif
>
