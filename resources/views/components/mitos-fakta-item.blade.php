<div {{ $attributes->merge(['class' => 'grid gap-3 sm:grid-cols-2']) }}>
    <div class="rounded-2xl border border-brand-amber/40 bg-brand-amber-soft p-4">
        <p class="flex items-center gap-2 font-semibold text-brand-ink mb-1">
            <span aria-hidden="true">❌</span> Mitos
        </p>
        <div class="text-sm text-brand-ink/80">{{ $mitos }}</div>
    </div>
    <div class="rounded-2xl border border-brand-line bg-brand-mint-soft p-4">
        <p class="flex items-center gap-2 font-semibold text-brand-ink mb-1">
            <span aria-hidden="true">✅</span> Fakta
        </p>
        <div class="text-sm text-brand-ink/80">{{ $fakta }}</div>
    </div>
</div>
