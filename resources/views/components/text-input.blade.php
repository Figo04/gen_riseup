@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-2xl border-brand-line bg-brand-cream placeholder:text-brand-ink/35 focus:border-brand-forest focus:ring-brand-forest disabled:opacity-60']) }}>
