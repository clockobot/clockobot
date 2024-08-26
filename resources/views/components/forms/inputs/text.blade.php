@props([
    'errors' => false,
    'name' => '',
    'readonly' => '',
    'deactivated' => false,
    'label' => '',
    'legend' => '',
    'class' => $attributes
])
@if($legend)
    <span class="block text-sm italic mb-1">{{$legend}}</span>
@endif
<input
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
