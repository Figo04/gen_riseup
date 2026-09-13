<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-brand-forest px-6 py-3 font-semibold text-white transition hover:bg-brand-forest-deep focus:outline-none focus:ring-2 focus:ring-brand-forest focus:ring-offset-2 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
