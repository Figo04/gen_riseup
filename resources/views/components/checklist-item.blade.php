@props(['variant' => 'check'])

@php
    $icon = $variant === 'warning' ? '⚠️' : '✅';
@endphp

<li {{ $attributes->merge(['class' => 'flex items-start gap-2 text-sm text-brand-ink/80']) }}>
    <span aria-hidden="true">{{ $icon }}</span>
    <span>{{ $slot }}</span>
</li>
