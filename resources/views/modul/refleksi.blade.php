<x-app-layout>
    @include('modul._band', [
        'kembaliUrl' => route('modul.sub-bagian.show', [$modul, $subBagian]),
        'kembaliLabel' => $subBagian->judul,
        'judul' => 'Lembar Refleksi',
        'sub' => null,
        'meta' => null,
    ])

    <div class="mx-auto max-w-md space-y-5 px-5 pt-5">
        @if (session('status'))
            <p class="rounded-2xl bg-brand-mint-soft p-4 text-sm text-brand-forest-deep">{{ session('status') }}</p>
        @endif

        {{-- Halaman ini hanya bisa diakses setelah materi selesai, jadi tab refleksi selalu terbuka. --}}
        @include('modul._tabs', ['aktif' => 'refleksi', 'sudahSelesai' => true])

        <div class="rounded-3xl bg-brand-paper p-5 shadow-sm">
            <p class="text-brand-ink/60">Ceritakan apa yang kamu pelajari atau rasakan dari bagian ini.</p>

            @if ($refleksi?->is_locked)
                <p class="mt-4 text-sm font-semibold text-brand-forest">✓ Refleksi sudah dikirim dan tidak dapat diubah.</p>
                <textarea class="mt-3 w-full rounded-2xl border-brand-line bg-brand-cream text-brand-ink/70" rows="6" disabled>{{ $refleksi->jawaban }}</textarea>
            @else
                <form method="POST" action="{{ route('modul.sub-bagian.refleksi.store', [$modul, $subBagian]) }}" class="mt-4 space-y-4">
                    @csrf
                    <textarea name="jawaban" rows="6" required
                              placeholder="Tulis sejujurnya aja, nggak ada jawaban yang salah…"
                              class="w-full rounded-2xl border-brand-line bg-brand-cream placeholder:text-brand-ink/35 focus:border-brand-forest focus:ring-brand-forest">{{ old('jawaban') }}</textarea>
                    <x-input-error :messages="$errors->get('jawaban')" />
                    <button type="submit" class="w-full rounded-full bg-brand-forest py-4 font-bold text-white">
                        Kirim Refleksi
                    </button>
                </form>
            @endif
        </div>

        <p class="rounded-2xl bg-brand-lilac p-4 text-sm text-brand-ink/70">
            Refleksi cuma bisa dikirim sekali, jadi santai aja dan tulis apa adanya.
        </p>
    </div>
</x-app-layout>
