<x-guest-layout>
    <h1 class="text-3xl font-bold">Bikin akun dulu</h1>
    <p class="mt-2 text-brand-ink/60">Datanya dipakai untuk penelitian, jadi isi sejujurnya ya.</p>

    <div class="mt-5 rounded-3xl bg-brand-paper p-6 shadow-sm">
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="name" value="Nama" />
                <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="usia" value="Usia" />
                    <x-text-input id="usia" class="mt-1 block w-full" type="number" name="usia" :value="old('usia')" min="13" max="18" required />
                    <x-input-error :messages="$errors->get('usia')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="kelas" value="Kelas" />
                    <x-text-input id="kelas" class="mt-1 block w-full" type="text" name="kelas" :value="old('kelas')" placeholder="9B" />
                    <x-input-error :messages="$errors->get('kelas')" class="mt-1" />
                </div>
            </div>

            <div>
                <x-input-label for="jenis_kelamin" value="Jenis kelamin" />
                <select id="jenis_kelamin" name="jenis_kelamin" required
                        class="mt-1 block w-full rounded-2xl border-brand-line bg-brand-cream focus:border-brand-forest focus:ring-brand-forest">
                    <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih jenis kelamin</option>
                    <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="sekolah" value="Sekolah" />
                <x-text-input id="sekolah" class="mt-1 block w-full" type="text" name="sekolah" :value="old('sekolah')" placeholder="SMPN 4 Bandung" />
                <x-input-error :messages="$errors->get('sekolah')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Ulangi password" />
                <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <button type="submit" class="w-full rounded-full bg-brand-forest py-4 font-bold text-white">Daftar</button>
        </form>
    </div>

    <p class="mt-5 text-center text-sm text-brand-ink/60">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-brand-forest underline">Masuk di sini</a>
    </p>
</x-guest-layout>
