<div class="{{ isset($parentClass) ? $parentClass : 'w-full' }}">
    <input type="text" name="{{ $name ?? '' }}" id="{{ $name ?? '' }}" {{ $attributes }}
    class="bg-gray-50 placeholder:italic placeholder:text-gray-600 rounded border border-gray-500/50 w-full py-2 px-3 border-gray-200 focus:ring-1 focus:ring-gray-400 focus:border-gray-400 disabled:bg-gray-100 disabled:text-gray-500/90 disabled:shadow-none focus:outline-none" autocomplete="off" data-date>
</div>
