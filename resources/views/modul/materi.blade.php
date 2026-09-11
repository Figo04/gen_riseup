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
