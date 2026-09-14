<x-admin-layout>
    <x-slot name="header">{{ $subBagian->judul }}</x-slot>

    <a href="{{ route('admin.materi.show', $modul) }}" class="text-sm text-indigo-600 hover:underline">&larr; {{ $modul->nama }}</a>

    @if ($subBagian->video_youtube_id)
        <p class="mt-4 text-sm text-gray-500">Video: {{ $subBagian->video_youtube_id }}</p>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 p-6 mt-4">
        @include($subBagian->viewKonten())
    </div>
</x-admin-layout>
