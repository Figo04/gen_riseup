@props(['title' => null, 'icon' => '🎯', 'variant' => 'mint'])

@php
    $variantClass = $variant === 'peach'
        ? 'border-brand-peach/40 bg-brand-peach-light'
        : 'border-brand-mint/30 bg-brand-mint-light';
@endphp

<div {{ $attributes->merge(['class' => "rounded-2xl border p-5 $variantClass"]) }}>
    @if ($title)
        <p class="flex items-center gap-2 font-semibold text-brand-ink mb-2">
            <span aria-hidden="true">{{ $icon }}</span>
            {{ $title }}
        </p>
    @endif
    <div class="text-sm text-brand-ink/80 space-y-2">
        {{ $slot }}
    </div>
</div>
