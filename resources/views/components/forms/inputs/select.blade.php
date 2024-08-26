@props([
    'errors' => false,
    'name' => '',
    'label' => '',
    'select_empty_label' => '',
    'deactivated' => 'false',
    'class' => '',
])

<div class="relative">
    @if($attributes['multiple'])
        <select
            name="{{ $name }}"
            {!! ($attributes['multiple'] ? 'multiple class="choices"' : $attributes) !!}

            @if($deactivated == 'true')
                disabled
            @endif
        >
            <option hidden disabled selected value> -- {{ __('Choisir une option') }} -- </option>

            @if(isset($options))
                {{ $options }}
            @endif
        </select>
    @else
        <select
            name="{{ $name }}"
            {{ $class }}
            {{ $attributes }}

            @if($deactivated == 'true')
                disabled
            @endif
        >
            @if($select_empty_label != '')
                <option readonly hidden selected value> -- {{ $select_empty_label }} -- </option>
            @endif

            @if(isset($options))
                {{ $options }}
            @endif
        </select>
    @endif
</div>

{{ $slot }}
