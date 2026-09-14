<x-admin-layout>
    <x-slot name="header">Hasil Test</x-slot>

    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <form method="GET" class="flex gap-2">
            <input
                type="search"
                name="cari"
                value="{{ $cari }}"
                placeholder="Cari nama, email, atau sekolah…"
                class="w-full max-w-sm rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Cari
            </button>
            @if ($cari)
                <a href="{{ route('admin.hasil-test.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-800">Reset</a>
            @endif
        </form>

        <div class="flex gap-2">
            <a href="{{ route('admin.export') }}"
               class="px-4 py-2 text-sm font-medium rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                Export CSV
            </a>
            <a href="{{ route('admin.export', ['format' => 'excel']) }}"
               class="px-4 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-700">
                Export Excel
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm text-left whitespace-nowrap">
            <thead class="text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-2 font-medium" rowspan="2">Nama</th>
                    <th class="px-5 py-2 font-medium border-l border-gray-100" colspan="3">Pre-Test</th>
                    <th class="px-5 py-2 font-medium border-l border-gray-100" colspan="3">Post-Test</th>
                    <th class="px-5 py-2 font-medium border-l border-gray-100" colspan="2">Selisih</th>
                </tr>
                <tr class="text-xs">
                    <th class="px-5 pb-2 font-medium border-l border-gray-100">Pengetahuan</th>
                    <th class="px-5 pb-2 font-medium">Kategori</th>
                    <th class="px-5 pb-2 font-medium">Sikap</th>
                    <th class="px-5 pb-2 font-medium border-l border-gray-100">Pengetahuan</th>
                    <th class="px-5 pb-2 font-medium">Kategori</th>
                    <th class="px-5 pb-2 font-medium">Sikap</th>
                    <th class="px-5 pb-2 font-medium border-l border-gray-100">Pengetahuan</th>
                    <th class="px-5 pb-2 font-medium">Sikap</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswa as $s)
                    @php
                        // unique(user_id, tipe_sesi) → maks 1 baris per tipe.
                        $pre = $s->hasilKuesioner->firstWhere('tipe_sesi', 'pre');
                        $post = $s->hasilKuesioner->firstWhere('tipe_sesi', 'post');
                    @endphp
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-5 py-3 text-gray-800">
                            {{ $s->name }}
                            <span class="block text-xs text-gray-400">{{ $s->sekolah ?? '-' }} · {{ $s->kelas ?? '-' }}</span>
                        </td>

                        @foreach ([$pre, $post] as $hasil)
                            <td class="px-5 py-3 text-gray-600 border-l border-gray-100">{{ $hasil ? (float) $hasil->skor_pengetahuan : '-' }}</td>
                            <td class="px-5 py-3">
                                @if ($hasil)
                                    <span @class([
                                        'rounded-full px-2 py-0.5 text-xs font-medium',
                                        'bg-green-50 text-green-700' => $hasil->kategori_pengetahuan === 'Baik',
                                        'bg-amber-50 text-amber-700' => $hasil->kategori_pengetahuan === 'Cukup',
                                        'bg-red-50 text-red-700' => $hasil->kategori_pengetahuan === 'Kurang',
                                    ])>{{ $hasil->kategori_pengetahuan }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $hasil ? (float) $hasil->skor_sikap : '-' }}</td>
                        @endforeach

                        @foreach (['skor_pengetahuan', 'skor_sikap'] as $i => $kolom)
                            <td @class([
                                'px-5 py-3 font-medium',
                                'border-l border-gray-100' => $i === 0,
                            ])>
                                @if ($pre && $post)
                                    @php $selisih = (float) $post->$kolom - (float) $pre->$kolom; @endphp
                                    <span @class([
                                        'text-green-600' => $selisih > 0,
                                        'text-red-600' => $selisih < 0,
                                        'text-gray-400' => $selisih == 0,
                                    ])>{{ $selisih > 0 ? '+' : '' }}{{ $selisih }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-5 py-6 text-center text-gray-400">
                            {{ $cari ? 'Tidak ada hasil yang cocok.' : 'Belum ada siswa yang mengerjakan test.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $siswa->links() }}</div>
</x-admin-layout>
