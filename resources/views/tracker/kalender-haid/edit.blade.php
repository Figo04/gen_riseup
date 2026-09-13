<x-app-layout>
    <header class="rounded-b-3xl bg-brand-pink px-5 pb-7 pt-8">
        <div class="mx-auto max-w-md">
            <a href="{{ route('kalender-haid.index') }}" class="inline-flex items-center gap-1 py-1 text-sm text-brand-ink/60">
                <span aria-hidden="true">&larr;</span> Kalender Haid
            </a>
            <h1 class="mt-2 text-2xl font-bold">Ubah catatan</h1>
        </div>
    </header>

    <div class="mx-auto max-w-md px-5 pt-5">
        <div class="rounded-3xl bg-brand-paper p-5 shadow-sm">
            <form method="POST" action="{{ route('kalender-haid.update', $entri) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-semibold text-brand-ink/70">Tanggal mulai</label>
                    <input id="tanggal_mulai" name="tanggal_mulai" type="date" required
                           value="{{ old('tanggal_mulai', $entri->tanggal_mulai->format('Y-m-d')) }}"
                           class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream focus:border-brand-forest focus:ring-brand-forest">
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-1" />
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-semibold text-brand-ink/70">Tanggal selesai (opsional)</label>
                    <input id="tanggal_selesai" name="tanggal_selesai" type="date"
                           value="{{ old('tanggal_selesai', $entri->tanggal_selesai?->format('Y-m-d')) }}"
                           class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream focus:border-brand-forest focus:ring-brand-forest">
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-1" />
                </div>
                <div>
                    <label for="catatan" class="block text-sm font-semibold text-brand-ink/70">Catatan gejala (opsional)</label>
                    <textarea id="catatan" name="catatan" rows="3" placeholder="Contoh: kram ringan, mudah lelah…"
                              class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream placeholder:text-brand-ink/35 focus:border-brand-forest focus:ring-brand-forest">{{ old('catatan', $entri->catatan) }}</textarea>
                    <x-input-error :messages="$errors->get('catatan')" class="mt-1" />
                </div>

                <button type="submit" class="w-full rounded-full bg-brand-forest py-4 font-bold text-white">Simpan catatan</button>
                <a href="{{ route('kalender-haid.index') }}" class="block py-2 text-center text-sm text-brand-ink/55">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>
