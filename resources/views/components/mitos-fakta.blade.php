@props(['title' => 'Mitos vs Fakta'])

<div {{ $attributes->merge(['class' => 'space-y-3']) }}>
    <p class="font-semibold text-brand-ink">{{ $title }}</p>
    <div class="space-y-3">
        {{ $slot }}
    </div>
</div>
