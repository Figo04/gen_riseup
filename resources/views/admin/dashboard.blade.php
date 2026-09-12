<x-admin-layout>
    <x-slot name="header">Dashboard</x-slot>

    <p class="text-gray-600 mb-6">Selamat datang, {{ auth('admin')->user()->nama }}.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Total Respondents</p>
            <p class="mt-1 text-3xl font-semibold text-gray-800">{{ $totalRespondents }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Pre-Test Completed</p>
            <p class="mt-1 text-3xl font-semibold text-gray-800">{{ $preCompleted }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Post-Test Completed</p>
            <p class="mt-1 text-3xl font-semibold text-gray-800">{{ $postCompleted }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Pre+Post Complete</p>
            <p class="mt-1 text-3xl font-semibold text-gray-800">{{ $prePostComplete }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">Recent Activity</h3>
        </div>
        <table class="w-full text-sm text-left">
            <thead class="text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-2 font-medium">Nama</th>
                    <th class="px-5 py-2 font-medium">Sesi</th>
                    <th class="px-5 py-2 font-medium">Kategori Pengetahuan</th>
                    <th class="px-5 py-2 font-medium">Skor Sikap</th>
                    <th class="px-5 py-2 font-medium">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentActivity as $hasil)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-5 py-3 text-gray-800">{{ $hasil->user->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ ucfirst($hasil->tipe_sesi) }}-Test</td>
                        <td class="px-5 py-3 text-gray-600">{{ $hasil->kategori_pengetahuan }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $hasil->skor_sikap }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $hasil->submitted_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-6 text-center text-gray-400">Belum ada aktivitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
