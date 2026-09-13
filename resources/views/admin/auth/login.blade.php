<x-guest-layout>
    {{-- Judul ditambahkan supaya halaman ini tidak tertukar dengan login siswa:
         keduanya memakai layout tamu yang sama dan sebelumnya sama-sama tanpa judul. --}}
    <h1 class="text-2xl font-bold">Masuk Admin</h1>
    <p class="mt-1 text-brand-ink/60">Halaman pengelola. Siswa masuk lewat halaman utama.</p>

    <form method="POST" action="{{ route('admin.login') }}" class="mt-5 rounded-3xl bg-brand-paper p-6 shadow-sm">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
