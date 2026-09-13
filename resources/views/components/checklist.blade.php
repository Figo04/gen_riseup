@props(['title' => null])

{{-- Kotak garis putus-putus, mengikuti "KEBIASAAN SEHAT SAAT HAID" di materi-4.png --}}
<div {{ $attributes->merge(['class' => 'rounded-2xl border border-dashed border-brand-forest/35 p-5']) }}>
    @if ($title)
        <p class="mb-3 text-sm font-bold uppercase tracking-wide text-brand-forest">{{ $title }}</p>
    @endif
    <ul class="list-none space-y-3">
        {{ $slot }}
    </ul>
</div>
