@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-brand-ink/70']) }}>
    {{ $value ?? $slot }}
</label>
