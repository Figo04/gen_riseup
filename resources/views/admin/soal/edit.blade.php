<x-admin-layout>
    <x-slot name="header">Edit Soal</x-slot>

    <form method="POST" action="{{ route('admin.soal.update', $soal) }}" class="bg-white rounded-lg border border-gray-200 p-6 max-w-xl">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
            <p class="text-gray-800">{{ ucfirst($soal->tipe) }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
            <textarea name="pertanyaan" rows="3" class="w-full rounded-md border-gray-300">{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
            @error('pertanyaan') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        @if ($soal->tipe === 'pengetahuan')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban Benar</label>
                <select name="jawaban_benar" class="w-full rounded-md border-gray-300">
                    <option value="B" @selected(old('jawaban_benar', $soal->jawaban_benar) === 'B')>Benar</option>
                    <option value="S" @selected(old('jawaban_benar', $soal->jawaban_benar) === 'S')>Salah</option>
                </select>
                @error('jawaban_benar') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        @else
            <div class="mb-6">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                    <input type="checkbox" name="reverse_scored" value="1" @checked(old('reverse_scored', $soal->reverse_scored))>
                    Reverse Scored
                </label>
            </div>
        @endif

        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
            Simpan
        </button>
        <a href="{{ route('admin.soal.index') }}" class="ml-3 text-sm text-gray-500 hover:text-gray-800">Batal</a>
    </form>
</x-admin-layout>
