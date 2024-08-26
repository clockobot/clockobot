@props([
    'errors' => false,
    'name' => '',
    'label' => '',
    'legend' => '',
    'class' => '',
    'radios' => ''
])

<div class="mt-2">
    <div class="flex space-x-4 items-center">
        @foreach($radios as $radio)
            <div class="flex space-x-2">
                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $radio['value'] }}"
                    {!! $attributes !!}

                    @if($radio['value'] == $attributes['active'])
                        checked
                    @endif
                >

                <span class="inline-block -mt-1">{{ $radio['label'] }}</span>
            </div>
        @endforeach
    </div>
</div>
