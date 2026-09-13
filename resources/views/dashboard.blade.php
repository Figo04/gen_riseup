@php
    $jam = (int) now()->format('H');
    $sapaan = match (true) {
        $jam < 11 => 'Halo, semangat pagi!',
        $jam < 15 => 'Halo, semangat siang!',
        $jam < 18 => 'Halo, semangat sore!',
        default => 'Halo, semangat malam!',
    };

    $progress = $modul->map(fn ($m) => [
        'modul' => $m,
        'selesai' => $m->subBagian->whereIn('id', $selesaiIds)->count(),
        'total' => $m->subBagian->count(),
    ]);
    $modulTuntas = $progress->filter(fn ($p) => $p['total'] > 0 && $p['selesai'] === $p['total'])->count();
    $lanjut = $progress->filter(fn ($p) => $p['selesai'] < $p['total'])->take(2);
    // Modul pertama yang belum tuntas ditandai nomor; sisanya ikon gembok.
    // Gembok = penanda "belum selesai" (PRD §3.1), bukan kunci — semua modul
    // tetap bisa dibuka setelah pre-test.
    $berjalanId = $lanjut->first()['modul']->id ?? null;
@endphp

<x-app-layout>
    <div class="mx-auto max-w-md px-5 pt-8">
        {{-- Pesan dari redirect gating (pre/post-test sudah diisi, modul belum selesai, dst.) --}}
        @if (session('status'))
            <p class="mb-5 rounded-2xl bg-brand-lilac p-4 text-sm text-brand-ink/75">{{ session('status') }}</p>
        @endif

        <p class="text-sm text-brand-ink/60">{{ $sapaan }}</p>
        <h1 class="mt-1 text-3xl font-bold">Hai {{ Auth::user()->name }} <span aria-hidden="true">👋</span></h1>
        <svg class="mt-1 h-3 w-32 text-brand-amber" viewBox="0 0 128 12" fill="none" aria-hidden="true">
            <path d="M2 8c14-8 28 4 42-2s28 6 42 0 20 2 20 2" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
        </svg>

        <p class="mt-5 text-brand-ink/70">
            Kamu sudah menyelesaikan {{ $modulTuntas }} dari {{ $modul->count() }} modul.<br>
            Pelan-pelan aja, yang penting jalan terus.
        </p>

        <img src="{{ asset('images/chara-2.png') }}" alt=""
             class="mx-auto mt-4 w-56" loading="lazy">

        {{-- Jejak belajarmu --}}
        <section class="mt-4 rounded-3xl bg-brand-forest p-6 text-white">
            <h2 class="flex items-center gap-2 text-lg font-bold">
                <svg class="h-5 w-5 text-brand-amber" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M3 7l4 4 5-6 5 6 4-4v11H3V7Z"/>
                </svg>
                Jejak belajarmu
            </h2>

            <ol class="mt-5 space-y-6">
                @foreach ($progress as $i => $p)
                    @php($tuntas = $p['total'] > 0 && $p['selesai'] === $p['total'])
                    <li class="relative flex gap-4">
                        @unless ($loop->last)
                            <span class="absolute left-[19px] top-10 h-full w-0.5 bg-white/25" aria-hidden="true"></span>
                        @endunless

                        <span class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full
                                     {{ $tuntas || $p['modul']->id === $berjalanId ? 'bg-white text-brand-forest' : 'bg-white/15 text-white/70' }}">
                            @if ($tuntas)
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 13 4 4L19 7"/>
                                </svg>
                                <span class="sr-only">Selesai</span>
                            @elseif ($p['modul']->id === $berjalanId)
                                <span class="font-bold">{{ $i + 1 }}</span>
                            @else
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>
                                </svg>
                                <span class="sr-only">Belum selesai</span>
                            @endif
                        </span>

                        <div class="min-w-0 flex-1 pt-0.5">
                            <p class="font-bold">{{ $p['modul']->nama }}</p>
                            <p class="text-sm text-white/70">{{ $p['selesai'] }}/{{ $p['total'] }} bagian selesai</p>
                            <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-white/20">
                                <div class="h-full rounded-full bg-brand-mint-soft"
                                     style="width: {{ $p['total'] ? round($p['selesai'] / $p['total'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        {{-- Pintasan --}}
        <div class="mt-5 grid grid-cols-2 gap-4">
            <a href="{{ route('modul.index') }}"
               class="rounded-3xl bg-brand-paper p-5 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-mint-soft text-brand-forest">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 6.5C10.5 5 8.5 4.5 4 4.5V19c4.5 0 6.5.5 8 2 1.5-1.5 3.5-2 8-2V4.5c-4.5 0-6.5.5-8 2Z"/><path d="M12 6.5V21"/>
                    </svg>
                </span>
                <p class="mt-8 text-lg font-bold">Materi</p>
                <p class="text-sm text-brand-ink/55">{{ $modul->count() }} modul seru menantimu</p>
            </a>

            @if ($sudahPretest && Auth::user()->materiSelesaiSemua())
                <a href="{{ route('kuesioner.posttest.create') }}"
                   class="rounded-3xl border-2 border-dashed border-brand-forest/40 bg-brand-mint-soft p-5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-brand-forest">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1"/><path d="m9 12.5 2 2 4-4"/>
                        </svg>
                    </span>
                    <p class="mt-8 text-lg font-bold">Post-test</p>
                    <p class="text-sm text-brand-ink/55">Terakhir, lalu kamu selesai</p>
                </a>
            @elseif (! $sudahPretest)
                <a href="{{ route('kuesioner.pretest.create') }}"
                   class="rounded-3xl border-2 border-dashed border-rose-300 bg-brand-pink-soft p-5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-rose-500">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1"/><path d="m9 12.5 2 2 4-4"/>
                        </svg>
                    </span>
                    <p class="mt-8 text-lg font-bold">Pre-test</p>
                    <p class="text-sm text-brand-ink/55">Santai, ini bukan ujian</p>
                </a>
            @else
                <div class="rounded-3xl bg-brand-paper p-5 shadow-sm">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-mint-soft text-brand-forest">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1"/><path d="m9 12.5 2 2 4-4"/>
                        </svg>
                    </span>
                    <p class="mt-8 text-lg font-bold">Pre-test</p>
                    <p class="text-sm text-brand-ink/55">Sudah kamu isi, makasih ya</p>
                </div>
            @endif
        </div>

        {{-- Lanjut dari sini --}}
        @if ($lanjut->isNotEmpty())
            <h2 class="mt-8 text-xl font-bold">Lanjut dari sini</h2>
            <div class="mt-3 space-y-3">
                @foreach ($lanjut as $i => $p)
                    <a href="{{ route('modul.show', $p['modul']) }}"
                       class="flex items-center justify-between gap-3 rounded-2xl p-4 {{ $i === 0 ? 'bg-brand-mint-soft' : 'bg-brand-amber-soft' }}">
                        <span class="min-w-0">
                            <span class="block font-bold">{{ $p['modul']->nama }}</span>
                            <span class="block text-sm text-brand-ink/60">{{ $p['modul']->subtitle }}</span>
                        </span>
                        <span class="shrink-0 rounded-full bg-white/80 px-3 py-1 text-sm font-semibold">
                            {{ $p['selesai'] }}/{{ $p['total'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
