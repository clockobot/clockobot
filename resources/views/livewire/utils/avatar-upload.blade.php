<div>
    <div class="max-w-2xl mx-auto p-2">
        <div class="text-day-7 dark:text-night-1">
            <div>
                <div>
                    <div class="flex flex-col space-y-4 justify-start items-center">
                        @if($photo)
                            <div class="relative w-24 h-24 overflow-hidden rounded-full border-2 border-night">
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover" wire:loading.class="opacity-50">

                                <div wire:loading class="absolute  z-10 w-8 h-8">
                                    <span class="loader"></span>
                                </div>
                            </div>
                        @else
                            <div class="relative w-24 h-24 overflow-hidden rounded-full text-day-7 dark:text-night-1 bg-day-1 dark:bg-night-3 border-2 border-day-3 dark:border-night-1 flex justify-center items-center text-xs shadow">
                                @if ($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" class="w-full h-full object-cover" wire:loading.class="opacity-50">
                                @else
                                    <img src="{{ asset('images/clockobot-avatar.jpg') }}" class="w-full h-full object-cover" alt="Avatar" wire:loading.class="opacity-50">
                                @endif
                                <div wire:loading class="absolute  z-10 w-8 h-8">
                                    <span class="loader"></span>
                                </div>
                            </div>
                        @endif


                        <input type="file" wire:model="photo" class="file_input" id="file">
                        <label for="file">
                            @if($photo || $user->avatar)
                            {{ __('timer.modify') }}
                            @else
                            {{ __('timer.upload') }}
                            @endif
                        </label>

                    </div>
                    @error('photo') <span class="error">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>
    </div>
</div>
