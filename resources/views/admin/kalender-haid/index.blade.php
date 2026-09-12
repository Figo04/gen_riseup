<x-admin-layout>
    <x-slot name="header">Data Kalender Haid</x-slot>

    <div class="bg-white rounded-lg border border-gray-200">
        <table class="w-full text-sm text-left">
            <thead class="text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-2 font-medium">Siswa</th>
                    <th class="px-5 py-2 font-medium">Mulai</th>
                    <th class="px-5 py-2 font-medium">Selesai</th>
                    <th class="px-5 py-2 font-medium">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($entri as $e)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-5 py-3 text-gray-800">{{ $e->user->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $e->tanggal_mulai->format('d M Y') }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $e->tanggal_selesai?->format('d M Y') ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $e->catatan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-6 text-center text-gray-400">Belum ada entri.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
