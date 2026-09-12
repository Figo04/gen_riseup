<x-admin-layout>
    <x-slot name="header">Kelola Materi</x-slot>

    <div class="bg-white rounded-lg border border-gray-200">
        <table class="w-full text-sm text-left">
            <thead class="text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-2 font-medium w-12">Urutan</th>
                    <th class="px-5 py-2 font-medium">Modul</th>
                    <th class="px-5 py-2 font-medium">Jumlah Sub-Bagian</th>
                    <th class="px-5 py-2 font-medium w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modul as $m)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-5 py-3 text-gray-500">{{ $m->urutan }}</td>
                        <td class="px-5 py-3 text-gray-800">{{ $m->nama }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $m->sub_bagian_count }}</td>
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.materi.show', $m) }}" class="text-indigo-600 hover:underline">Lihat</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
