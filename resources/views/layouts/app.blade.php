<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-brand-line font-sans antialiased text-brand-ink">
        {{-- Desain ini mobile-first dan tiap halaman mengunci dirinya di max-w-md.
             Di layar lebar kolom itu dibingkai jadi panel aplikasi di tengah supaya
             tidak tampak seperti pita nyasar; di HP panel = selebar layar, jadi
             bayangan & garisnya baru muncul dari sm ke atas. --}}
        <div class="mx-auto min-h-screen w-full max-w-md bg-brand-cream pb-24 sm:shadow-xl sm:ring-1 sm:ring-black/5">
            <!-- Page Heading -->
            @isset($header)
                <header class="mx-auto max-w-md px-5 pt-8">
                    {{ $header }}
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <x-bottom-nav />
    </body>
</html>
