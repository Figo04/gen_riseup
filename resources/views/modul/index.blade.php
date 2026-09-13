@php
    $ikonClass = [
        'mint' => 'bg-brand-mint-soft',
        'amber' => 'bg-brand-amber-soft',
        'lilac' => 'bg-brand-lilac',
        'pink' => 'bg-brand-pink',
    ];
@endphp

<x-app-layout>
    <div class="mx-auto max-w-md px-5 pt-8">
        <h1 class="text-3xl font-bold">Materi</h1>
        <p class="mt-2 text-brand-ink/60">Empat modul, satu per satu. Nggak usah buru-buru.</p>

        <div class="mt-6 space-y-4">
            @foreach ($modul as $m)
                @php($jumlahSelesai = $m->subBagian->whereIn('id', $selesaiIds)->count())
                @php($total = $m->subBagian->count())
                @php($mulai = $jumlahSelesai > 0)

                {{-- Modul yang belum disentuh tampil redup sebagai penanda "belum mulai",
                     tapi tetap bisa dibuka — semua modul terbuka setelah pre-test (PRD §3.1). --}}
                <a href="{{ route('modul.show', $m) }}"
                   class="flex items-center gap-4 rounded-3xl p-5 {{ $mulai ? 'bg-brand-paper shadow-sm' : 'bg-brand-paper/60' }}">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-xl {{ $mulai ? $ikonClass[$m->warna] : 'bg-brand-line/60 grayscale' }}"
                          aria-hidden="true">{{ $m->ikon }}</span>

                    <span class="min-w-0 flex-1">
                        <span class="block text-xs font-semibold uppercase tracking-wide text-brand-ink/45">Modul {{ $loop->iteration }}</span>
                        <span class="block text-lg font-bold {{ $mulai ? '' : 'text-brand-ink/70' }}">{{ $m->nama }}</span>
                        <span class="block text-sm text-brand-ink/55">{{ $m->subtitle }}</span>

                        <span class="mt-3 flex items-center gap-3">
                            <span class="h-1.5 flex-1 overflow-hidden rounded-full bg-brand-line">
                                <span class="block h-full rounded-full bg-brand-forest"
                                      style="width: {{ $total ? round($jumlahSelesai / $total * 100) : 0 }}%"></span>
                            </span>
                            <span class="shrink-0 whitespace-nowrap text-sm font-semibold text-brand-ink/60">{{ $jumlahSelesai }}/{{ $total }}</span>
                        </span>
                    </span>

                    <svg class="h-5 w-5 shrink-0 text-brand-ink/40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m9 5 7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
