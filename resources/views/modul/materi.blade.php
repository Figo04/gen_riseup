<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $subBagian->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg p-4">
                    {{ session('status') }}
                </div>
            @endif

            @include('modul._tabs', ['aktif' => 'materi'])

            @if ($subBagian->video_youtube_id)
                <button type="button" x-data class="inline-flex items-center gap-2 text-sm font-semibold text-brand-ink" x-on:click="$dispatch('open-modal', 'video-materi')">
                    ▶ Tonton Video
                </button>

                <x-modal name="video-materi" maxWidth="2xl">
                    <div class="aspect-video">
                        <iframe
                            class="w-full h-full"
                            :src="show ? 'https://www.youtube-nocookie.com/embed/{{ $subBagian->video_youtube_id }}' : ''"
                            allow="encrypted-media"
                            allowfullscreen
                        ></iframe>
                    </div>
                </x-modal>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @include($subBagian->konten_view)
            </div>

            <form method="POST" action="{{ route('modul.sub-bagian.selesai', [$modul, $subBagian]) }}">
                @csrf
                @if ($sudahSelesai)
                    <p class="text-sm text-green-600">✓ Materi ini sudah ditandai selesai.</p>
                @else
                    <x-primary-button type="submit">Tandai Materi Selesai</x-primary-button>
                @endif
            </form>
        </div>
    </div>
</x-app-layout>
