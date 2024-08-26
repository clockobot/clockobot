@props([
    'errors' => false,
    'name' => '',
    'label' => '',
    'readonly' => '',
    'deactivated' => false,
    'type' => 'text',
    'class' => 'resize-none',
    'value' => '',
    'rows' => 6,
])

<textarea
{{--    x-data="{ resize: () => { $el.style.height = '64px'; $el.style.height = $el.scrollHeight + 'px' } }"--}}
{{--    @input="resize()"--}}
    id="{{ $name }}"
    type="text"
    autocorrect="off"
    spellcheck="false"
    autocapitalize="none"
    name="{{ $name }}"
{{--    style="resize: none; height: 62px;overflow-y: hidden;"--}}
    rows="{{ $rows }}"
    {{ $attributes }}
    {{ $class }}

    @if($deactivated == 'true')
        disabled
    @endif
>{{ $value }}</textarea>
