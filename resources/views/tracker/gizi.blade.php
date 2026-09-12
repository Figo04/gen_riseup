<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tracker Gizi Mingguan
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
                <p class="text-sm text-gray-500">
                    Isi tabel ini sekali untuk seminggu sebagai pengingat sederhana kebiasaan makanmu.
                </p>

                @if ($tracker?->is_locked)
                    <p class="text-sm text-green-600">✓ Tracker gizi sudah dikirim dan tidak dapat diubah.</p>
                @endif

                <form method="POST" action="{{ route('tracker-gizi.store') }}">
                    @csrf
                    <div class="overflow-x-auto rounded-2xl border border-gray-200">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-700 font-semibold">
                                <tr>
                                    <th class="p-3">Hari</th>
                                    <th class="p-3">Sayur &amp; Buah?</th>
                                    <th class="p-3">Sumber Protein?</th>
                                    <th class="p-3">Air Putih Cukup?</th>
                                    <th class="p-3">Camilan Sehat?</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hari as $h)
                                    <tr class="border-t border-gray-100">
                                        <td class="p-3">{{ $h }}</td>
                                        @foreach ($kebiasaan as $k)
                                            <td class="p-3">
                                                <input
                                                    type="checkbox"
                                                    name="data[{{ $h }}][{{ $k }}]"
                                                    value="1"
                                                    @checked($tracker?->data[$h][$k] ?? false)
                                                    @disabled($tracker?->is_locked)
                                                >
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @unless ($tracker?->is_locked)
                        <x-primary-button type="submit" class="mt-4">Kirim Tracker</x-primary-button>
                    @endunless
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
