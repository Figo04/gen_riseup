@props(['active' => false, 'disabled' => false])

@if ($disabled)
    <span {{ $attributes->merge(['class' => 'flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 cursor-not-allowed']) }}>
        {{ $slot }}
    </span>
@else
    @php
    $classes = $active
                ? 'flex items-center px-3 py-2 rounded-md text-sm font-medium bg-indigo-50 text-indigo-700'
                : 'flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900';
    @endphp
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@endif
