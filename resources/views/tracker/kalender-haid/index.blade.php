@php
    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $namaHari = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    $kosongAwal = $bulan->dayOfWeekIso - 1;
    $hariIni = now()->toDateString();
@endphp

<x-app-layout>
    <div class="mx-auto max-w-md px-5 pt-8" x-data="{ formTerbuka: {{ $errors->any() ? 'true' : 'false' }} }">
        <h1 class="text-3xl font-bold">Kalender Haid</h1>
        <p class="mt-2 text-brand-ink/60">Catat siklusmu tiap bulan supaya kamu makin kenal pola tubuhmu.</p>

        @if (session('status'))
            <p class="mt-5 rounded-2xl bg-brand-mint-soft p-4 text-sm text-brand-forest-deep">{{ session('status') }}</p>
        @endif

        {{-- Grid bulanan --}}
        <div class="mt-6 rounded-3xl bg-brand-paper p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <a href="{{ route('kalender-haid.index', ['bulan' => $bulan->copy()->subMonth()->format('Y-m')]) }}"
                   class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-mint-soft text-brand-forest"
                   aria-label="Bulan sebelumnya">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 5-7 7 7 7"/></svg>
                </a>
                <p class="font-bold">{{ $namaBulan[(int) $bulan->format('n')] }} {{ $bulan->format('Y') }}</p>
                <a href="{{ route('kalender-haid.index', ['bulan' => $bulan->copy()->addMonth()->format('Y-m')]) }}"
                   class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-mint-soft text-brand-forest"
                   aria-label="Bulan berikutnya">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 5 7 7-7 7"/></svg>
                </a>
            </div>

            <div class="mt-5 grid grid-cols-7 gap-y-3 text-center text-sm">
                @foreach ($namaHari as $h)
                    <div class="text-xs font-semibold text-brand-ink/50">{{ $h }}</div>
                @endforeach

                @for ($i = 0; $i < $kosongAwal; $i++)
                    <div></div>
                @endfor

                @for ($d = 1; $d <= $bulan->daysInMonth; $d++)
                    @php($tanggal = $bulan->copy()->day($d)->toDateString())
                    <div>
                        <span class="mx-auto flex h-9 w-9 items-center justify-center rounded-full
                            @if (in_array($tanggal, $hariHaid, true)) bg-brand-pink font-semibold text-rose-700
                            @elseif ($tanggal === $hariIni) bg-brand-mint-soft font-semibold text-brand-forest
                            @else text-brand-ink/70 @endif">{{ $d }}</span>
                    </div>
                @endfor
            </div>

            <p class="mt-4 flex items-center justify-center gap-2 text-xs text-brand-ink/50">
                <span class="inline-block h-3 w-3 rounded-full bg-brand-pink"></span> hari haid tercatat
            </p>
        </div>

        {{-- Tombol + form tambah --}}
        <button type="button" x-show="! formTerbuka"
                class="mt-5 flex w-full items-center justify-center gap-2 rounded-full bg-brand-forest py-4 font-bold text-white"
                x-on:click="formTerbuka = true">
            <span aria-hidden="true">+</span> Catat haid bulan ini
        </button>

        <div x-show="formTerbuka" x-cloak class="mt-5 rounded-3xl bg-brand-paper p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <h2 class="text-lg font-bold">Catat haid baru</h2>
                <button type="button" x-on:click="formTerbuka = false" aria-label="Tutup form"
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-line/60 text-brand-ink/60">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('kalender-haid.store') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-semibold text-brand-ink/70">Tanggal mulai</label>
                    <input id="tanggal_mulai" name="tanggal_mulai" type="date" required value="{{ old('tanggal_mulai') }}"
                           class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream focus:border-brand-forest focus:ring-brand-forest">
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-1" />
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-semibold text-brand-ink/70">Tanggal selesai (opsional)</label>
                    <input id="tanggal_selesai" name="tanggal_selesai" type="date" value="{{ old('tanggal_selesai') }}"
                           class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream focus:border-brand-forest focus:ring-brand-forest">
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-1" />
                </div>
                <div>
                    <label for="catatan" class="block text-sm font-semibold text-brand-ink/70">Catatan gejala (opsional)</label>
                    <textarea id="catatan" name="catatan" rows="3" placeholder="Contoh: kram ringan, mudah lelah…"
                              class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream placeholder:text-brand-ink/35 focus:border-brand-forest focus:ring-brand-forest">{{ old('catatan') }}</textarea>
                    <x-input-error :messages="$errors->get('catatan')" class="mt-1" />
                </div>
                <button type="submit" class="w-full rounded-full bg-brand-forest py-4 font-bold text-white">Simpan catatan</button>
            </form>
        </div>

        {{-- Riwayat --}}
        <h2 class="mt-8 text-xl font-bold">Riwayat</h2>
        @if ($entri->isEmpty())
            <p class="mt-3 rounded-2xl bg-brand-paper p-5 text-sm text-brand-ink/55 shadow-sm">
                Belum ada catatan. Mulai dari haid terakhirmu, ya.
            </p>
        @else
            <ul class="mt-3 space-y-3">
                @foreach ($entri as $e)
                    <li class="flex items-center gap-3 rounded-2xl bg-brand-paper p-4 shadow-sm">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-pink text-lg" aria-hidden="true">🩸</span>

                        <a href="{{ route('kalender-haid.edit', $e) }}" class="min-w-0 flex-1">
                            <span class="block font-semibold">
                                {{-- locale di-set per pemanggilan, bukan lewat APP_LOCALE, supaya tidak mengubah apa pun di luar tampilan tanggal ini --}}
                                {{ $e->tanggal_mulai->locale('id')->translatedFormat('j M Y') }}
                                @if ($e->tanggal_selesai)
                                    &mdash; {{ $e->tanggal_selesai->locale('id')->translatedFormat('j M Y') }}
                                @endif
                            </span>
                            <span class="block text-sm text-brand-ink/55">{{ $e->catatan ?: 'Ketuk untuk mengubah' }}</span>
                        </a>

                        <form method="POST" action="{{ route('kalender-haid.destroy', $e) }}"
                              onsubmit="return confirm('Hapus catatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Hapus catatan"
                                    class="flex h-10 w-10 items-center justify-center rounded-full text-brand-ink/45">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13"/>
                                </svg>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>
