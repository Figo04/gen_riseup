<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Entri Kalender Haid
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <form method="POST" action="{{ route('kalender-haid.update', $entri) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="tanggal_mulai" value="Tanggal Mulai" />
                        <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date" class="mt-1 block w-full" value="{{ old('tanggal_mulai', $entri->tanggal_mulai->format('Y-m-d')) }}" required />
                        <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="tanggal_selesai" value="Tanggal Selesai (opsional)" />
                        <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date" class="mt-1 block w-full" value="{{ old('tanggal_selesai', $entri->tanggal_selesai?->format('Y-m-d')) }}" />
                        <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="catatan" value="Catatan (opsional)" />
                        <textarea id="catatan" name="catatan" class="mt-1 block w-full rounded-lg border-gray-300" rows="3">{{ old('catatan', $entri->catatan) }}</textarea>
                        <x-input-error :messages="$errors->get('catatan')" class="mt-1" />
                    </div>
                    <div class="flex items-center gap-4">
                        <x-primary-button type="submit">Simpan</x-primary-button>
                        <a href="{{ route('kalender-haid.index') }}" class="text-sm text-gray-500 underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
