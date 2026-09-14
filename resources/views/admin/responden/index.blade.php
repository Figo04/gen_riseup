<x-admin-layout>
    <x-slot name="header">Responden</x-slot>

    <form method="GET" class="mb-4 flex gap-2">
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
            <a href="{{ route('admin.responden.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-800">Reset</a>
        @endif
    </form>

    <div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-2 font-medium">Nama</th>
                    <th class="px-5 py-2 font-medium">Email</th>
                    <th class="px-5 py-2 font-medium">Usia</th>
                    <th class="px-5 py-2 font-medium">JK</th>
                    <th class="px-5 py-2 font-medium">Sekolah</th>
                    <th class="px-5 py-2 font-medium">Kelas</th>
                    <th class="px-5 py-2 font-medium">Materi</th>
                    <th class="px-5 py-2 font-medium">Pre-Test</th>
                    <th class="px-5 py-2 font-medium">Post-Test</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswa as $s)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-5 py-3 text-gray-800">{{ $s->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $s->email }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $s->usia }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $s->jenis_kelamin }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $s->sekolah ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $s->kelas ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600 whitespace-nowrap">
                            {{ $s->materi_selesai_count }} / {{ $totalSubBagian }}
                        </td>
                        @foreach (['pre', 'post'] as $tipe)
                            <td class="px-5 py-3">
                                @if ($s->hasilKuesioner->contains('tipe_sesi', $tipe))
                                    <span class="rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700">Selesai</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">Belum</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-5 py-6 text-center text-gray-400">
                            {{ $cari ? 'Tidak ada responden yang cocok.' : 'Belum ada responden.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $siswa->links() }}</div>
</x-admin-layout>
