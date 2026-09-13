@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-2xl bg-brand-mint-soft p-4 text-sm font-medium text-brand-forest-deep']) }}>
        {{ $status }}
    </div>
@endif
