<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-full border border-brand-line bg-brand-paper px-6 py-3 font-semibold text-brand-ink/70 transition hover:bg-brand-cream focus:outline-none focus:ring-2 focus:ring-brand-forest focus:ring-offset-2 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
