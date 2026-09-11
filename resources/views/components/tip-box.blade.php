@props(['title' => 'Tips Praktis', 'icon' => '💡'])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-brand-mint/30 bg-brand-mint-light p-5']) }}>
    <p class="flex items-center gap-2 font-semibold text-brand-ink mb-2">
        <span aria-hidden="true">{{ $icon }}</span>
        {{ $title }}
    </p>
    <div class="text-sm text-brand-ink/80 space-y-2">
        {{ $slot }}
    </div>
</div>
