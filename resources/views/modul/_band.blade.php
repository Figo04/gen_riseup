@php
    // Class Tailwind ditulis penuh di sini supaya ikut ke-scan (lihat Modul::getWarnaAttribute).
    $bandClass = [
        'mint' => 'bg-brand-mint-soft',
        'amber' => 'bg-brand-amber-soft',
        'lilac' => 'bg-brand-lilac',
        'pink' => 'bg-brand-pink',
    ][$modul->warna];
@endphp

<header class="{{ $bandClass }} rounded-b-3xl px-5 pb-7 pt-8">
    <div class="mx-auto max-w-md">
        <a href="{{ $kembaliUrl }}" class="inline-flex items-center gap-1 py-1 text-sm text-brand-ink/60">
            <span aria-hidden="true">&larr;</span> {{ $kembaliLabel }}
        </a>
        <h1 class="mt-2 text-2xl font-bold">{{ $judul }}</h1>
        @if (! empty($sub))
            <p class="mt-1 text-brand-ink/60">{{ $sub }}</p>
        @endif
        @if (! empty($meta))
            <p class="mt-3 text-sm font-semibold">{{ $meta }}</p>
        @endif
    </div>
</header>
