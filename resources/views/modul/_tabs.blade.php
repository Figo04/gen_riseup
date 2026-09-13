@php($refleksiTerbuka = $sudahSelesai ?? true)
{{-- Lembar refleksi cuma satu per modul (lihat config/refleksi.php), jadi tab-nya
     hanya muncul di sub-bagian terakhir; sub-bagian lain tampil full-width. --}}
@php($adaRefleksi = $subBagian->adalahTerakhir())

<nav class="grid gap-3 {{ $adaRefleksi ? 'grid-cols-2' : 'grid-cols-1' }}">
    <a href="{{ route('modul.sub-bagian.show', [$modul, $subBagian]) }}"
       @if ($aktif === 'materi') aria-current="page" @endif
       class="rounded-full py-3 text-center font-semibold {{ $aktif === 'materi' ? 'bg-brand-forest text-white' : 'bg-brand-line/50 text-brand-ink/60' }}">
        Materi
    </a>

    @if (! $adaRefleksi)
        {{-- Sub-bagian ini tidak punya lembar refleksi sendiri. --}}
    @elseif ($refleksiTerbuka)
        <a href="{{ route('modul.sub-bagian.refleksi', [$modul, $subBagian]) }}"
           @if ($aktif === 'refleksi') aria-current="page" @endif
           class="rounded-full py-3 text-center font-semibold {{ $aktif === 'refleksi' ? 'bg-brand-forest text-white' : 'bg-brand-line/50 text-brand-ink/60' }}">
            Refleksi
        </a>
    @else
        {{-- Refleksi baru terbuka setelah materi ditandai selesai (dijaga di RefleksiController). --}}
        <span class="flex items-center justify-center gap-1.5 rounded-full bg-brand-line/50 py-3 font-semibold text-brand-ink/35"
              title="Tandai materi selesai dulu untuk membuka refleksi">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>
            </svg>
            Refleksi
        </span>
    @endif
</nav>
