@php($jumlahSelesai = $modul->subBagian->whereIn('id', $selesaiIds)->count())

<x-app-layout>
    @include('modul._band', [
        'kembaliUrl' => route('modul.index'),
        'kembaliLabel' => 'Kembali',
        'judul' => $modul->nama,
        'sub' => $modul->subtitle,
        'meta' => $jumlahSelesai.'/'.$modul->subBagian->count().' bagian selesai',
    ])

    <div class="mx-auto max-w-md space-y-3 px-5 pt-6">
        @if (session('status'))
            <p class="rounded-2xl bg-brand-mint-soft p-4 text-sm text-brand-forest-deep">{{ session('status') }}</p>
        @endif

        @foreach ($modul->subBagian->sortBy('urutan') as $sub)
            @php($selesai = $selesaiIds->contains($sub->id))
            <a href="{{ route('modul.sub-bagian.show', [$modul, $sub]) }}"
               class="flex items-center gap-4 rounded-2xl bg-brand-paper p-4 shadow-sm">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold
                             {{ $selesai ? 'bg-brand-forest text-white' : 'bg-brand-mint-soft text-brand-forest' }}">
                    @if ($selesai)
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                    @else
                        {{ $loop->iteration }}
                    @endif
                </span>

                <span class="min-w-0 flex-1">
                    <span class="block font-semibold">{{ $sub->judul }}</span>
                    <span class="block text-sm {{ $selesai ? 'text-brand-forest' : 'text-brand-ink/50' }}">
                        {{ $selesai ? '✓ Selesai' : 'Belum selesai' }}
                    </span>
                </span>

                <svg class="h-5 w-5 shrink-0 text-brand-ink/40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m9 5 7 7-7 7"/>
                </svg>
            </a>
        @endforeach
    </div>
</x-app-layout>
