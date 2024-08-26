@props([
    'errors' => false,
    'name' => '',
    'label' => '',
    'legend' => '',
    'deactivated' => 'false',
    'readonly' => '',
    'consignes' => '',
    'active' => '',
    'type' => 'text',
    'value' => '',
    'radios' => '',
    'choices' => '',
    'rows' => 6,
    'parentClass' => 'mb-2 w-full',
    'class' => $attributes
    ->class([
        'focus:ring-red-400 focus:border-red-400' => $errors->has($name),
        'focus:ring-gray-400 focus:border-gray-400' => !$errors->has($name)
    ])
    ->merge([
        'class' => $attributes['classes'] . ' bg-white text-day-7 border border-day-3 dark:bg-night-5 dark:border-night-4 dark:text-night-1 placeholder:italic placeholder:text-day-6 dark:placeholder:text-night-3 rounded w-full py-1.5 px-2 focus:ring-1 disabled:bg-gray-100 disabled:text-gray-500/90 disabled:shadow-none focus:outline-none '
    ]),
])

<div class="{{ $parentClass ? $parentClass : 'mb-2 w-full' }}">
    <div>
        @if(isset($label) && !empty($label))
            <label for="{{ $name }}" class="font-medium text-sm text-day-6 dark:text-night-2 inline-block mb-1">{!! $label !!}{!! ($attributes['required'] && !isset($attributes['disabled']) ? ' <span class="required text-red-500">*</span>' : '') !!}</label>
        @endif

        @if(isset($consignes) && !empty($consignes))
            <p class="text-sm italic mb-1.5 text-day-7 dark:text-night-1">{!! $consignes !!}</p>
        @endif

        @switch($type)
            @case('textarea')
                <x-forms.inputs.textarea name="{{ $name }}" id="{{ $name }}" label="{{ $label }}" {{ $attributes }} value="{{ $value }}" :deactivated="$deactivated" :rows="$rows" :class="$class" />
            @break

            @case('email')
                <x-forms.inputs.text type="email" name="{{ $name }}" id="{{ $name }}" label="{{ $label }}" {{ $attributes }} value="{{ $value }}" :deactivated="$deactivated" :class="$class" />
            @break

            @case('number')
                <x-forms.inputs.text type="number" name="{{ $name }}" id="{{ $name }}" label="{{ $label }}" legend="{{ $legend }}" {{ $attributes }} value="{{ $value }}" :deactivated="$deactivated" :class="$class" />
            @break

            @case('checkbox')
                <x-forms.inputs.checkbox name="{{ $name }}" id="{{ $name }}" label="{{ $label }}" legend="{{ $legend }}" {{ $attributes }} active="{{ $active }}" value="{{ $value }}" :deactivated="$deactivated" :class="$class" />
            @break

            @case('radio')
                <x-forms.inputs.radio :radios="$radios" name="{{ $name }}" id="{{ $name }}" label="{{ $label }}" legend="{{ $legend }}" {{ $attributes }} active="{{ $active }}" value="{{ $value }}" :deactivated="$deactivated" :class="$class" />
            @break

            @case('file')
                <x-forms.inputs.file name="{{ $name }}" id="{{ $name }}" label="{{ $label }}" legend="{{ $legend }}" {{ $attributes }} value="{{ $value }}" :deactivated="$deactivated" :class="$class" />
            @break

            @case('url')
                <x-forms.inputs.text type="url" name="{{ $name }}" id="{{ $name }}" label="{{ $label }}" {{ $attributes }} value="{{ $value }}" :deactivated="$deactivated" :class="$class" />
            @break

            @case('time')
                <x-forms.inputs.text type="time" name="{{ $name }}" id="{{ $name }}" label="{{ $label }}" {{ $attributes }} value="{{ $value }}" :deactivated="$deactivated" :class="$class" />
            @break

            @case('date')
                <x-forms.inputs.text type="date" name="{{ $name }}" id="{{ $name }}" label="{{ $label }}" {{ $attributes }} value="{{ $value }}" :deactivated="$deactivated" :class="$class" />
            @break

            @case('datetime')
                <x-forms.inputs.text type="datetime-local" name="{{ $name }}" id="{{ $name }}" label="{{ $label }}" {{ $attributes }} value="{{ $value }}" :deactivated="$deactivated" :class="$class" />
            @break

            @case('select')
                <x-forms.inputs.select name="{{ $name }}" id="{{ $name }}" label="{{ $label }}" {{ $attributes }} value="{{ $value }}" :deactivated="$deactivated" :class="$class">
                    <x-slot name="options">{{ $options }}</x-slot>

                    {{ $slot }}
                </x-forms.inputs.select>
            @break

            @default
                <x-forms.inputs.text name="{{ $name }}" label="{{ $label }}" {{ $attributes }} value="{{ $value }}" :deactivated="$deactivated" :class="$class" />
        @endswitch

        @if($errors->has($name))
            <p class="text-red-500 font-semibold text-sm mt-0.5 mb-2">{{ $errors->first($name) }}</p>
        @endif
    </div>
</div>
