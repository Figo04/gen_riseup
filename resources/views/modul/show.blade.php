<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $modul->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg p-4">
                    {{ session('status') }}
                </div>
            @endif

            @foreach ($modul->subBagian->sortBy('urutan') as $sub)
                <a href="{{ route('modul.sub-bagian.show', [$modul, $sub]) }}" class="flex items-center justify-between gap-4 bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <span class="text-gray-800">{{ $sub->judul }}</span>
                    @if ($selesaiIds->contains($sub->id))
                        <span class="text-sm text-green-600 shrink-0 whitespace-nowrap">✓ Selesai</span>
                    @else
                        <span class="text-sm text-gray-400 shrink-0 whitespace-nowrap">Belum selesai</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
