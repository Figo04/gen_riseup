<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kalender Haid
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg p-4">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="font-semibold text-gray-800">Tambah Entri</h3>
                <form method="POST" action="{{ route('kalender-haid.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="tanggal_mulai" value="Tanggal Mulai" />
                        <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date" class="mt-1 block w-full" value="{{ old('tanggal_mulai') }}" required />
                        <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="tanggal_selesai" value="Tanggal Selesai (opsional)" />
                        <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date" class="mt-1 block w-full" value="{{ old('tanggal_selesai') }}" />
                        <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="catatan" value="Catatan (opsional)" />
                        <textarea id="catatan" name="catatan" class="mt-1 block w-full rounded-lg border-gray-300" rows="3">{{ old('catatan') }}</textarea>
                        <x-input-error :messages="$errors->get('catatan')" class="mt-1" />
                    </div>
                    <x-primary-button type="submit">Tambah</x-primary-button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Riwayat</h3>
                @if ($entri->isEmpty())
                    <p class="text-sm text-gray-500">Belum ada entri.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-700 font-semibold">
                                <tr>
                                    <th class="p-3">Mulai</th>
                                    <th class="p-3">Selesai</th>
                                    <th class="p-3">Catatan</th>
                                    <th class="p-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($entri as $e)
                                    <tr class="border-t border-gray-100">
                                        <td class="p-3">{{ $e->tanggal_mulai->format('d M Y') }}</td>
                                        <td class="p-3">{{ $e->tanggal_selesai?->format('d M Y') ?? '-' }}</td>
                                        <td class="p-3">{{ $e->catatan ?? '-' }}</td>
                                        <td class="p-3 space-x-2 whitespace-nowrap">
                                            <a href="{{ route('kalender-haid.edit', $e) }}" class="text-brand-ink underline">Edit</a>
                                            <form method="POST" action="{{ route('kalender-haid.destroy', $e) }}" class="inline" onsubmit="return confirm('Hapus entri ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
