@php
    // Urutan sikap mengikuti mockup test-2.png: rendah → tinggi.
    // Nilai yang dikirim tidak berubah, jadi skoring di controller tidak terpengaruh.
    $opsiSikap = [
        'STS' => 'Sangat tidak setuju',
        'TS' => 'Tidak setuju',
        'S' => 'Setuju',
        'SS' => 'Sangat setuju',
    ];
    $pill = 'flex items-center justify-center gap-2 rounded-full border border-brand-line bg-brand-paper px-3 py-3 text-center text-sm font-medium'
        .' peer-checked:border-brand-forest peer-checked:bg-brand-forest peer-checked:text-white'
        .' peer-focus-visible:ring-2 peer-focus-visible:ring-brand-forest peer-focus-visible:ring-offset-2';
@endphp

<div class="mx-auto max-w-md px-5 pt-8">
    <h1 class="text-3xl font-bold">{{ $judul }}</h1>
    <p class="mt-2 text-brand-ink/60">{{ $subjudul }}</p>

    <p class="mt-5 rounded-2xl bg-brand-lilac p-4 text-sm text-brand-ink/75">{{ $peringatan }}</p>

    <form method="POST" action="{{ $formAction }}" class="mt-5 space-y-5">
        @csrf

        <section class="rounded-3xl bg-brand-paper p-5 shadow-sm">
            <h2 class="text-lg font-bold">Bagian 1 &middot; Pengetahuan</h2>
            <p class="mt-1 text-sm text-brand-ink/55">Benar atau salah? Pilih yang menurutmu paling tepat.</p>

            <div class="mt-4 space-y-3">
                @foreach ($pengetahuan as $soal)
                    <fieldset class="rounded-2xl bg-brand-cream p-4">
                        <legend class="sr-only">Soal pengetahuan nomor {{ $soal->urutan }}</legend>
                        <p class="font-medium">{{ $soal->urutan }}. {{ $soal->pertanyaan }}</p>

                        <div class="mt-3 grid grid-cols-2 gap-3">
                            @foreach (['B' => '👍 Benar', 'S' => '👎 Salah'] as $value => $label)
                                <label class="block cursor-pointer">
                                    <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $value }}" required class="peer sr-only">
                                    <span class="{{ $pill }}">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        <x-input-error :messages="$errors->get('jawaban.' . $soal->id)" class="mt-2" />
                    </fieldset>
                @endforeach
            </div>
        </section>

        <section class="rounded-3xl bg-brand-paper p-5 shadow-sm">
            <h2 class="text-lg font-bold">Bagian 2 &middot; Sikap</h2>
            <p class="mt-1 text-sm text-brand-ink/55">Seberapa setuju kamu dengan pernyataan ini?</p>

            <div class="mt-4 space-y-3">
                @foreach ($sikap as $soal)
                    <fieldset class="rounded-2xl bg-brand-cream p-4">
                        <legend class="sr-only">Soal sikap nomor {{ $soal->urutan }}</legend>
                        <p class="font-medium">{{ $soal->urutan }}. {{ $soal->pertanyaan }}</p>

                        <div class="mt-3 grid grid-cols-2 gap-3">
                            @foreach ($opsiSikap as $value => $label)
                                <label class="block cursor-pointer">
                                    <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $value }}" required class="peer sr-only">
                                    <span class="{{ $pill }}">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        <x-input-error :messages="$errors->get('jawaban.' . $soal->id)" class="mt-2" />
                    </fieldset>
                @endforeach
            </div>
        </section>

        <button type="submit" class="w-full rounded-full bg-brand-forest py-4 font-bold text-white">
            {{ $tombolLabel }}
        </button>
    </form>
</div>
