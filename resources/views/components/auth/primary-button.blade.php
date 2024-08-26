<button {{ $attributes->merge(['type' => 'submit', 'class' => 'cursor-pointer flex items-center px-4 py-2 rounded shadow-sm text-sm font-medium text-white bg-app hover:bg-app-contrast transition-colors focus:outline-none focus:ring-0 transition-colors ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
