@props(['user'])

<div class="w-8 h-8 lg:w-10 lg:h-10 overflow-hidden rounded-full text-day-7 dark:text-night-1 bg-day-1 dark:bg-night-5 border border-day-4 dark:border-night-4 flex justify-center items-center text-xs shadow">
    @if($user->avatar)
        <img src="{{ Storage::url($user->avatar) }}" class="w-full h-full object-cover" alt="{{ $user->name . ' - ' }}">
    @else
        <img src="{{ asset('images/clockobot-avatar.jpg') }}" class="w-full h-full object-cover" alt="{{ $user->name . ' - ' }}">
    @endif
</div>
