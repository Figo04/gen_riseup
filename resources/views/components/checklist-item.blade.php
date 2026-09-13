@props(['variant' => 'check'])

<li {{ $attributes->merge(['class' => 'flex items-start gap-3 text-sm text-brand-ink/80']) }}>
    @if ($variant === 'warning')
        <span aria-hidden="true">⚠️</span>
    @else
        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-brand-mint-soft text-brand-forest" aria-hidden="true">
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                 stroke-linecap="round" stroke-linejoin="round"><path d="m5 13 4 4L19 7"/></svg>
        </span>
    @endif
    <span>{{ $slot }}</span>
</li>
