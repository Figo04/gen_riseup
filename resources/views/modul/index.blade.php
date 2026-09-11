<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Materi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @foreach ($modul as $m)
                @php($jumlahSelesai = $m->subBagian->whereIn('id', $selesaiIds)->count())
                <a href="{{ route('modul.show', $m) }}" class="block bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="font-semibold text-lg text-gray-800">{{ $m->nama }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $jumlahSelesai }} / {{ $m->subBagian->count() }} sub-bagian selesai</p>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
