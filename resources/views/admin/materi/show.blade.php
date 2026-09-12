<x-admin-layout>
    <x-slot name="header">{{ $modul->nama }}</x-slot>

    <a href="{{ route('admin.materi.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kelola Materi</a>

    <div class="bg-white rounded-lg border border-gray-200 mt-4">
        <table class="w-full text-sm text-left">
            <thead class="text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-2 font-medium w-12">Urutan</th>
                    <th class="px-5 py-2 font-medium">Sub-Bagian</th>
                    <th class="px-5 py-2 font-medium w-20">Video</th>
                    <th class="px-5 py-2 font-medium w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subBagian as $sb)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-5 py-3 text-gray-500">{{ $sb->urutan }}</td>
                        <td class="px-5 py-3 text-gray-800">{{ $sb->judul }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $sb->video_youtube_id ? '✓' : '-' }}</td>
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.materi.sub-bagian', [$modul, $sb]) }}" class="text-indigo-600 hover:underline">Lihat</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
