<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-lg p-4">
            {{ $peringatan }}
        </div>

        <form method="POST" action="{{ $formAction }}" class="space-y-6">
            @csrf

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-800 mb-1">Bagian II — Kuesioner Pengetahuan</h3>
                <p class="text-sm text-gray-500 mb-4">Berilah tanda pada kolom BENAR atau SALAH sesuai pengetahuan Adik.</p>

                <div class="space-y-4">
                    @foreach ($pengetahuan as $soal)
                        <div>
                            <p class="text-gray-800">{{ $soal->urutan }}. {{ $soal->pertanyaan }}</p>
                            <div class="flex gap-6 mt-1">
                                @foreach (['B' => 'Benar', 'S' => 'Salah'] as $value => $label)
                                    <label class="inline-flex items-center gap-1 text-sm text-gray-600">
                                        <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $value }}" required class="text-indigo-600 focus:ring-indigo-500">
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('jawaban.' . $soal->id)" class="mt-1" />
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-800 mb-1">Bagian III — Kuesioner Sikap</h3>
                <p class="text-sm text-gray-500 mb-4">Tidak ada jawaban benar atau salah. Jawablah sesuai apa yang Adik rasakan.</p>

                <div class="space-y-4">
                    @foreach ($sikap as $soal)
                        <div>
                            <p class="text-gray-800">{{ $soal->urutan }}. {{ $soal->pertanyaan }}</p>
                            <div class="flex flex-wrap gap-6 mt-1">
                                @foreach (['SS' => 'Sangat Setuju', 'S' => 'Setuju', 'TS' => 'Tidak Setuju', 'STS' => 'Sangat Tidak Setuju'] as $value => $label)
                                    <label class="inline-flex items-center gap-1 text-sm text-gray-600">
                                        <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $value }}" required class="text-indigo-600 focus:ring-indigo-500">
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('jawaban.' . $soal->id)" class="mt-1" />
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end">
                <x-primary-button>{{ $tombolLabel }}</x-primary-button>
            </div>
        </form>
    </div>
</div>
