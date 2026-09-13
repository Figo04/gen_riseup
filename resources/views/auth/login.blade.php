<x-guest-layout>
    <img src="{{ asset('images/chara-1.png') }}" alt="" class="mx-auto w-40">

    <h1 class="mt-2 text-center text-3xl font-bold">Selamat datang</h1>
    <p class="mt-2 text-center text-brand-ink/60">Masuk dulu yuk, biar perjalanan belajarmu tersimpan.</p>

    <x-auth-session-status class="mt-5" :status="session('status')" />

    <div class="mt-5 rounded-3xl bg-brand-paper p-6 shadow-sm">
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <label for="remember_me" class="flex items-center gap-2 py-1 text-sm text-brand-ink/60">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-brand-line text-brand-forest focus:ring-brand-forest">
                Ingat saya di perangkat ini
            </label>

            <button type="submit" class="w-full rounded-full bg-brand-forest py-4 font-bold text-white">Masuk</button>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="block py-2 text-center text-sm text-brand-ink/55">
                    Lupa password?
                </a>
            @endif
        </form>
    </div>

    <p class="mt-5 text-center text-sm text-brand-ink/60">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-brand-forest underline">Daftar di sini</a>
    </p>
</x-guest-layout>
