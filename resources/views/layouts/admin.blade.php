<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin - {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-100">
            <!-- Sidebar -->
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform transition-transform duration-150 ease-in-out lg:translate-x-0 lg:static lg:inset-auto"
            >
                <div class="h-16 flex items-center px-6 border-b border-gray-200">
                    <span class="text-lg font-semibold text-gray-800">GenResilUp++ Admin</span>
                </div>

                <nav class="p-4 space-y-6">
                    <div>
                        <p class="px-2 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Data</p>
                        <div class="space-y-1">
                            <x-admin-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                Dashboard
                            </x-admin-nav-link>
                            <x-admin-nav-link :href="route('admin.responden.index')" :active="request()->routeIs('admin.responden.*')">
                                Responden
                            </x-admin-nav-link>
                            <x-admin-nav-link disabled>Hasil Test</x-admin-nav-link>
                        </div>
                    </div>

                    <div>
                        <p class="px-2 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Konten</p>
                        <div class="space-y-1">
                            <x-admin-nav-link :href="route('admin.soal.index')" :active="request()->routeIs('admin.soal.*')">
                                Kelola Soal
                            </x-admin-nav-link>
                            <x-admin-nav-link :href="route('admin.materi.index')" :active="request()->routeIs('admin.materi.*')">
                                Kelola Materi
                            </x-admin-nav-link>
                            <x-admin-nav-link :href="route('admin.kalender-haid.index')" :active="request()->routeIs('admin.kalender-haid.*')">
                                Data Kalender Haid
                            </x-admin-nav-link>
                        </div>
                    </div>
                </nav>
            </aside>

            <!-- Backdrop (mobile) -->
            <div
                x-show="sidebarOpen"
                x-cloak
                @click="sidebarOpen = false"
                class="fixed inset-0 z-20 bg-black/30 lg:hidden"
            ></div>

            <!-- Main column -->
            <div class="flex-1 flex flex-col min-w-0">
                <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6">
                    <button @click="sidebarOpen = ! sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    @isset($header)
                        <div class="font-semibold text-gray-800">{{ $header }}</div>
                    @else
                        <div></div>
                    @endisset

                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600">{{ auth('admin')->user()->nama }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-800">
                                Log out
                            </button>
                        </form>
                    </div>
                </header>

                <main class="flex-1 p-4 sm:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
