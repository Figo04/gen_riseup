<x-app-layout>
    @include('modul._band', [
        'kembaliUrl' => route('modul.show', $modul),
        'kembaliLabel' => $modul->nama,
        'judul' => $subBagian->judul,
        'sub' => null,
        'meta' => null,
    ])

    <div class="mx-auto max-w-md space-y-5 px-5 pt-5">
        @if (session('status'))
            <p class="rounded-2xl bg-brand-mint-soft p-4 text-sm text-brand-forest-deep">{{ session('status') }}</p>
        @endif

        @include('modul._tabs', ['aktif' => 'materi'])

        <div class="space-y-4">
            @include($subBagian->viewKonten())
        </div>

        @if ($subBagian->video_youtube_id)
            <button type="button" x-data
                    class="flex w-full items-center gap-4 rounded-2xl bg-brand-ink p-4 text-left text-white"
                    x-on:click="$dispatch('open-modal', 'video-materi')">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-brand-amber text-brand-amber">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                </span>
                <span class="min-w-0">
                    <span class="block font-bold">Video: {{ $subBagian->judul }}</span>
                    <span class="block text-sm text-brand-amber">Putar langsung di halaman ini</span>
                </span>
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

        <form method="POST" action="{{ route('modul.sub-bagian.selesai', [$modul, $subBagian]) }}">
            @csrf
            @if ($sudahSelesai)
                <p class="rounded-full bg-brand-mint-soft py-3 text-center font-semibold text-brand-forest-deep">
                    ✓ Materi ini sudah ditandai selesai.
                </p>
            @else
                <button type="submit" class="w-full rounded-full bg-brand-forest py-4 font-bold text-white">
                    Tandai sudah dibaca
                </button>
            @endif
        </form>
    </div>
</x-app-layout>
