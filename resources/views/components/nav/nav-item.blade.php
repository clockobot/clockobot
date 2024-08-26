<li x-cloak>
    <a wire:navigate href="{{ $href }}" class="nav-el {{ $isActive ? 'active' : '' }} group">
        {{ $slot }}
        <span x-cloak :class="{ 'hidden': !sidebarOpen }" class="whitespace-nowrap">{{ $label }}</span>
    </a>
</li>
