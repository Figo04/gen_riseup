<x-app-layout>
    @include('modul._band', [
        'kembaliUrl' => route('modul.sub-bagian.show', [$modul, $subBagian]),
        'kembaliLabel' => $subBagian->judul,
        'judul' => 'Lembar Refleksi',
        'sub' => $lembar['judul'],
        'meta' => null,
    ])

    @php($terkunci = (bool) $refleksi?->is_locked)
    @php($isi = $refleksi?->jawaban ?? [])

    <div class="mx-auto max-w-md space-y-5 px-5 pt-5">
        @if (session('status'))
            <p class="rounded-2xl bg-brand-mint-soft p-4 text-sm text-brand-forest-deep">{{ session('status') }}</p>
        @endif

        {{-- Halaman ini hanya bisa diakses setelah materi selesai, jadi tab refleksi selalu terbuka. --}}
        @include('modul._tabs', ['aktif' => 'refleksi', 'sudahSelesai' => true])

        <form method="POST" action="{{ route('modul.sub-bagian.refleksi.store', [$modul, $subBagian]) }}" class="space-y-5">
            @csrf

            <div class="rounded-3xl bg-brand-paper p-5 shadow-sm">
                @if (isset($lembar['pengantar']))
                    <p class="text-brand-ink/60">{{ $lembar['pengantar'] }}</p>
                @endif

                @if ($terkunci)
                    <p class="mt-4 text-sm font-semibold text-brand-forest">✓ Refleksi sudah dikirim dan tidak dapat diubah.</p>
                @endif

                <div class="mt-4 space-y-5">
                    @foreach ($lembar['pertanyaan'] as $i => $pertanyaan)
                        <div>
                            <label for="pertanyaan-{{ $i }}" class="block font-semibold text-brand-ink">
                                📝 {{ $pertanyaan }}
                            </label>
                            <textarea id="pertanyaan-{{ $i }}" name="pertanyaan[{{ $i }}]" rows="4"
                                      @disabled($terkunci) @required(! $terkunci)
                                      placeholder="Tulis sejujurnya aja, nggak ada jawaban yang salah…"
                                      class="mt-2 w-full rounded-2xl border-brand-line bg-brand-cream placeholder:text-brand-ink/35 focus:border-brand-forest focus:ring-brand-forest disabled:text-brand-ink/70"
                            >{{ old("pertanyaan.$i", $isi['pertanyaan'][$i] ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('pertanyaan.'.$i)" />
                        </div>
                    @endforeach
                </div>
            </div>

            @isset($lembar['tabel'])
                @php($tabel = $lembar['tabel'])
                @php($barisTersimpan = $isi['tabel'] ?? [])
                @php($jumlahBaris = $terkunci ? count($barisTersimpan) : max($tabel['baris'], count(old('tabel', []))))

                <div class="rounded-3xl bg-brand-paper p-5 shadow-sm">
                    <p class="font-semibold text-brand-ink">{{ $tabel['judul'] }}</p>

                    <div class="mt-3 overflow-x-auto rounded-2xl border border-brand-line">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-brand-mint-soft font-semibold text-brand-ink">
                                <tr>
                                    @foreach ($tabel['kolom'] as $kolom)
                                        <th class="p-3">{{ $kolom }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="text-brand-ink/80">
                                <tr class="border-t border-brand-line">
                                    @foreach ($tabel['contoh'] as $sel)
                                        <td class="p-3 italic text-brand-ink/50">{{ $sel }}</td>
                                    @endforeach
                                </tr>

                                @for ($b = 0; $b < $jumlahBaris; $b++)
                                    <tr class="border-t border-brand-line">
                                        @foreach ($tabel['kolom'] as $k => $kolom)
                                            <td class="p-1">
                                                <input type="text" name="tabel[{{ $b }}][{{ $k }}]"
                                                       @disabled($terkunci)
                                                       aria-label="{{ $kolom }} baris {{ $b + 1 }}"
                                                       value="{{ old("tabel.$b.$k", $barisTersimpan[$b][$k] ?? '') }}"
                                                       class="w-full min-w-32 rounded-xl border-transparent bg-transparent text-sm focus:border-brand-forest focus:bg-brand-cream focus:ring-brand-forest disabled:text-brand-ink/70">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    @unless ($terkunci)
                        <p class="mt-3 text-sm text-brand-ink/50">Baris yang dibiarkan kosong tidak akan disimpan.</p>
                    @endunless
                </div>
            @endisset

            @unless ($terkunci)
                <button type="submit" class="w-full rounded-full bg-brand-forest py-4 font-bold text-white">
                    Kirim Refleksi
                </button>
            @endunless
        </form>

        @isset($lembar['tautan'])
            <div class="rounded-3xl bg-brand-paper p-5 shadow-sm">
                <p class="font-semibold text-brand-ink">{{ $lembar['tautan']['judul'] }}</p>
                <p class="mt-1 text-sm text-brand-ink/60">{{ $lembar['tautan']['teks'] }}</p>
                <a href="{{ route($lembar['tautan']['route']) }}"
                   class="mt-3 block rounded-full bg-brand-mint py-3 text-center font-semibold text-brand-ink">
                    {{ $lembar['tautan']['label'] }}
                </a>
            </div>
        @endisset

        <p class="rounded-2xl bg-brand-lilac p-4 text-sm text-brand-ink/70">
            Refleksi cuma bisa dikirim sekali, jadi santai aja dan tulis apa adanya.
        </p>
    </div>
</x-app-layout>
