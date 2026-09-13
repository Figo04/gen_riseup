@php
    $labelKebiasaan = [
        'sayur_buah' => '🥬 Sayur & buah',
        'protein' => '🍳 Sumber protein',
        'air_putih' => '💧 Air putih cukup',
        'camilan_sehat' => '🍎 Camilan sehat',
    ];
    $terkunci = (bool) $tracker?->is_locked;
@endphp

<x-app-layout>
    <div class="mx-auto max-w-md px-5 pt-8">
        <h1 class="text-3xl font-bold">Tracker Gizi Mingguan</h1>
        <p class="mt-2 text-brand-ink/60">Isi sekali untuk seminggu, sebagai pengingat sederhana kebiasaan makanmu.</p>

        @if (session('status'))
            <p class="mt-5 rounded-2xl bg-brand-mint-soft p-4 text-sm text-brand-forest-deep">{{ session('status') }}</p>
        @endif

        @if ($terkunci)
            <p class="mt-5 rounded-2xl bg-brand-mint-soft p-4 text-sm font-semibold text-brand-forest-deep">
                ✓ Tracker gizi sudah dikirim dan tidak dapat diubah.
            </p>
        @endif

        <form method="POST" action="{{ route('tracker-gizi.store') }}" class="mt-5 space-y-3">
            @csrf

            {{-- Tabel 7x4 diganti kartu per hari: di layar 390px tabel harus digeser
                 ke samping, sedangkan kartu tetap terbaca tanpa scroll horizontal. --}}
            @foreach ($hari as $h)
                {{-- role="group" + aria-labelledby, bukan fieldset/legend: legend dirender
                     browser di area border (labelnya jadi melayang di luar kartu), dan
                     akal-akalan float untuk menariknya masuk bikin grid di bawahnya
                     menyusut ke min-content lalu meluber keluar kartu. --}}
                <div role="group" aria-labelledby="hari-{{ $h }}" class="rounded-2xl bg-brand-paper p-4 shadow-sm">
                    <p id="hari-{{ $h }}" class="mb-2 font-bold">{{ $h }}</p>

                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($kebiasaan as $k)
                            <label class="block {{ $terkunci ? '' : 'cursor-pointer' }}">
                                <input type="checkbox" name="data[{{ $h }}][{{ $k }}]" value="1"
                                       class="peer sr-only"
                                       @checked($tracker?->data[$h][$k] ?? false)
                                       @disabled($terkunci)>
                                <span class="flex h-full items-center gap-2 rounded-xl border border-brand-line bg-brand-cream px-3 py-2 text-sm
                                             peer-checked:border-brand-forest peer-checked:bg-brand-mint-soft peer-checked:font-semibold peer-checked:text-brand-forest-deep
                                             peer-focus-visible:ring-2 peer-focus-visible:ring-brand-forest peer-disabled:opacity-70">
                                    {{ $labelKebiasaan[$k] ?? $k }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @unless ($terkunci)
                <button type="submit" class="w-full rounded-full bg-brand-forest py-4 font-bold text-white">Kirim Tracker</button>
                <p class="pt-1 text-center text-sm text-brand-ink/55">Sekali dikirim, isinya tidak bisa diubah lagi.</p>
            @endunless
        </form>
    </div>
</x-app-layout>
