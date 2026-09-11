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

            @include('modul._tabs', ['aktif' => 'refleksi'])

            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <p class="text-sm text-gray-500">Ceritakan apa yang kamu pelajari atau rasakan dari bagian ini.</p>

                @if ($refleksi?->is_locked)
                    <p class="text-sm text-green-600">✓ Refleksi sudah dikirim dan tidak dapat diubah.</p>
                    <textarea class="w-full rounded-lg border-gray-300" rows="6" disabled>{{ $refleksi->jawaban }}</textarea>
                @else
                    <form method="POST" action="{{ route('modul.sub-bagian.refleksi.store', [$modul, $subBagian]) }}" class="space-y-4">
                        @csrf
                        <textarea name="jawaban" class="w-full rounded-lg border-gray-300" rows="6" required>{{ old('jawaban') }}</textarea>
                        <x-input-error :messages="$errors->get('jawaban')" />
                        <x-primary-button type="submit">Kirim Refleksi</x-primary-button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
