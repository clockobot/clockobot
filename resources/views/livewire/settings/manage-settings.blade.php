<div>
    <div class="max-w-4xl mx-auto">
        <div class="">
            <div class="p-6 text-day-7 dark:text-night-1 lg:grid lg:grid-cols-6 lg:gap-x-10">
                <div class="lg:col-span-2 bg-day-1 dark:bg-night-6 flex items-center justify-center rounded shadow mb-4 lg:mb-0">
                    @livewire('utils.avatar-upload', ['user' => $user])
                </div>

                <div class="md:col-span-4">
                    <x-forms.inputs.field name="name" wire:model="name" wire:keydown.enter="save" label="{{ __('timer.username') }}" />
                    <x-forms.inputs.field type="email" name="email" wire:model="email" wire:keydown.enter="save" label="{{ __('timer.email') }}" />

                    <x-forms.inputs.field wire:model="locale" name="locale" type="select" label="{{ __('timer.language') }}">
                        <x-slot name="options">
                            <option value=""> -- {{ __('timer.choose') }} -- </option>
                            <option value="en">{{ __('timer.english') }}</option>
                            <option value="fr">{{ __('timer.french') }}</option>
                            <option value="de">{{ __('timer.german') }}</option>
                            <option value="es">{{ __('timer.spanish') }}</option>

                        </x-slot>
                    </x-forms.inputs.field>
                </div>

                <div class="col-span-6 mt-5 flex justify-end">
                    <x-buttons.primary wire:click="save">{{ __('timer.save') }}</x-buttons.primary>
                </div>
            </div>
        </div>
    </div>
</div>
