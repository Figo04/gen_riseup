@php
    $baris = [
        ['label' => 'Nama', 'nilai' => $user->name, 'ikon' => 'user'],
        ['label' => 'Usia', 'nilai' => $user->usia ? $user->usia.' tahun' : '—', 'ikon' => 'cake'],
        ['label' => 'Sekolah', 'nilai' => $user->sekolah ?: '—', 'ikon' => 'school'],
        ['label' => 'Kelas', 'nilai' => $user->kelas ?: '—', 'ikon' => 'cap'],
    ];

    $ikonPath = [
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4.5 21a7.5 7.5 0 0 1 15 0"/>',
        'cake' => '<path d="M4 21h16v-7H4z"/><path d="M4 14c1.5 0 1.5-2 3-2s1.5 2 3 2 1.5-2 3-2 1.5 2 3 2 1.5-2 3-2"/><path d="M12 8V5"/>',
        'school' => '<path d="M4 21V9l8-5 8 5v12"/><path d="M9 21v-6h6v6"/>',
        'cap' => '<path d="m3 9 9-4 9 4-9 4-9-4Z"/><path d="M7 11v4c0 1.7 2.2 3 5 3s5-1.3 5-3v-4"/>',
    ];
@endphp

<x-app-layout>
    <div class="mx-auto max-w-md px-5 pt-8" x-data="{ ubah: {{ $errors->getBag('default')->isNotEmpty() ? 'true' : 'false' }} }">
        <h1 class="text-3xl font-bold">Profil</h1>
        <svg class="mt-1 h-3 w-28 text-brand-amber" viewBox="0 0 128 12" fill="none" aria-hidden="true">
            <path d="M2 8c14-8 28 4 42-2s28 6 42 0 20 2 20 2" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
        </svg>

        {{-- Kartu identitas --}}
        <div class="mt-5 flex items-center gap-4 rounded-3xl bg-brand-forest p-5 text-white">
            <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-brand-amber text-2xl font-bold text-brand-ink"
                  aria-hidden="true">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
            <span class="min-w-0">
                <span class="block text-xl font-bold">{{ $user->name }}</span>
                <span class="block text-white/70">{{ $modulSelesai }} dari {{ $totalModul }} modul selesai</span>
            </span>
        </div>

        {{-- Data pribadi --}}
        <div class="mt-5 rounded-3xl bg-brand-paper p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-lg font-bold">Data pribadi</h2>
                <button type="button" x-show="! ubah" x-on:click="ubah = true"
                        class="flex items-center gap-1.5 rounded-full bg-brand-mint-soft px-4 py-2 text-sm font-semibold text-brand-forest">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 20h4L19 9a2.1 2.1 0 0 0-3-3L5 17v3Z"/>
                    </svg>
                    Ubah
                </button>
            </div>

            {{-- Tampilan baca --}}
            <div x-show="! ubah" class="mt-4 space-y-3">
                @foreach ($baris as $b)
                    <div class="flex items-center gap-3 rounded-2xl bg-brand-cream p-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-mint-soft text-brand-forest">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                {!! $ikonPath[$b['ikon']] !!}
                            </svg>
                        </span>
                        <span class="min-w-0">
                            <span class="block text-xs font-semibold uppercase tracking-wide text-brand-ink/45">{{ $b['label'] }}</span>
                            <span class="block font-medium">{{ $b['nilai'] }}</span>
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- Tampilan ubah --}}
            <form method="post" action="{{ route('profile.update') }}" x-show="ubah" x-cloak class="mt-4 space-y-4">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-sm font-semibold text-brand-ink/70">Nama</label>
                    <input id="name" name="name" type="text" required autocomplete="name" value="{{ old('name', $user->name) }}"
                           class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream focus:border-brand-forest focus:ring-brand-forest">
                    <x-input-error class="mt-1" :messages="$errors->get('name')" />
                </div>

                <div>
                    <label for="usia" class="block text-sm font-semibold text-brand-ink/70">Usia</label>
                    <input id="usia" name="usia" type="number" min="13" max="18" required value="{{ old('usia', $user->usia) }}"
                           class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream focus:border-brand-forest focus:ring-brand-forest">
                    <x-input-error class="mt-1" :messages="$errors->get('usia')" />
                </div>

                <div>
                    <label for="sekolah" class="block text-sm font-semibold text-brand-ink/70">Sekolah</label>
                    <input id="sekolah" name="sekolah" type="text" value="{{ old('sekolah', $user->sekolah) }}"
                           class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream focus:border-brand-forest focus:ring-brand-forest">
                    <x-input-error class="mt-1" :messages="$errors->get('sekolah')" />
                </div>

                <div>
                    <label for="kelas" class="block text-sm font-semibold text-brand-ink/70">Kelas</label>
                    <input id="kelas" name="kelas" type="text" value="{{ old('kelas', $user->kelas) }}"
                           class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream focus:border-brand-forest focus:ring-brand-forest">
                    <x-input-error class="mt-1" :messages="$errors->get('kelas')" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-brand-ink/70">Email</label>
                    <input id="email" name="email" type="email" required autocomplete="username" value="{{ old('email', $user->email) }}"
                           class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream focus:border-brand-forest focus:ring-brand-forest">
                    <x-input-error class="mt-1" :messages="$errors->get('email')" />
                </div>

                <button type="submit" class="w-full rounded-full bg-brand-forest py-4 font-bold text-white">Simpan perubahan</button>
                <button type="button" x-on:click="ubah = false" class="block w-full py-2 text-center text-sm text-brand-ink/55">Batal</button>
            </form>

            @if (session('status') === 'profile-updated')
                <p class="mt-4 rounded-2xl bg-brand-mint-soft p-3 text-center text-sm font-semibold text-brand-forest-deep">Perubahan tersimpan.</p>
            @endif
        </div>

        <p class="mt-5 rounded-2xl bg-brand-lilac p-4 text-center text-sm text-brand-ink/75">
            Terus melangkah, pelan-pelan aja. Kamu nggak sendirian <span aria-hidden="true">🧡</span>
        </p>

        {{-- Pengaturan akun: tidak ada di mockup, tapi tetap dibutuhkan.
             Disembunyikan di balik <details> supaya tidak mengganggu tampilan utama. --}}
        <details class="mt-5 rounded-3xl bg-brand-paper p-5 shadow-sm">
            <summary class="cursor-pointer font-bold">Pengaturan akun</summary>

            <div class="mt-4 space-y-6">
                @include('profile.partials.update-password-form')
                @include('profile.partials.delete-user-form')
            </div>
        </details>

        <form method="POST" action="{{ route('logout') }}" class="mt-5">
            @csrf
            <button type="submit" class="w-full rounded-full border border-brand-line bg-brand-paper py-3 font-semibold text-brand-ink/70">
                Keluar
            </button>
        </form>
    </div>
</x-app-layout>
