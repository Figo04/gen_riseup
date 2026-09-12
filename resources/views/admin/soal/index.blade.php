<x-admin-layout>
    <x-slot name="header">Kelola Soal</x-slot>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-6">
        <a href="{{ route('admin.soal.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
            + Tambah Soal
        </a>
    </div>

    @foreach (['pengetahuan' => $pengetahuan, 'sikap' => $sikap] as $tipe => $daftar)
        <div class="bg-white rounded-lg border border-gray-200 mb-8">
            <div class="px-5 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Soal {{ ucfirst($tipe) }} ({{ $daftar->count() }})</h3>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-2 font-medium w-12">No</th>
                        <th class="px-5 py-2 font-medium">Pertanyaan</th>
                        <th class="px-5 py-2 font-medium">
                            {{ $tipe === 'pengetahuan' ? 'Jawaban Benar' : 'Reverse Scored' }}
                        </th>
                        <th class="px-5 py-2 font-medium w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $soal)
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="px-5 py-3 text-gray-500">{{ $soal->urutan }}</td>
                            <td class="px-5 py-3 text-gray-800">{{ $soal->pertanyaan }}</td>
                            <td class="px-5 py-3 text-gray-600">
                                {{ $tipe === 'pengetahuan' ? $soal->jawaban_benar : ($soal->reverse_scored ? 'Ya' : 'Tidak') }}
                            </td>
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.soal.edit', $soal) }}" class="text-indigo-600 hover:underline mr-3">Edit</a>
                                <form action="{{ route('admin.soal.destroy', $soal) }}" method="POST" class="inline" onsubmit="return confirm('Hapus soal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-6 text-center text-gray-400">Belum ada soal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach
</x-admin-layout>
