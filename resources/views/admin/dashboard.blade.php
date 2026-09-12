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

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500 mb-3">Status Test</p>
            <canvas id="chartTestStatus"></canvas>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500 mb-3">Progress Materi</p>
            <canvas id="chartMaterialStatus"></canvas>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500 mb-3">Jenis Kelamin</p>
            <canvas id="chartGender"></canvas>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500 mb-3">Usia</p>
            <canvas id="chartAge"></canvas>
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

    <script>
        new Chart(document.getElementById('chartTestStatus'), {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($testStatus)),
                datasets: [{ data: @json(array_values($testStatus)), backgroundColor: ['#e5e7eb', '#93c5fd', '#3b82f6'] }],
            },
        });
        new Chart(document.getElementById('chartMaterialStatus'), {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($materialStatus)),
                datasets: [{ data: @json(array_values($materialStatus)), backgroundColor: ['#3b82f6', '#e5e7eb'] }],
            },
        });
        new Chart(document.getElementById('chartGender'), {
            type: 'bar',
            data: {
                labels: @json($genderDistribution->keys()),
                datasets: [{ label: 'Jumlah', data: @json($genderDistribution->values()), backgroundColor: '#3b82f6' }],
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
        });
        new Chart(document.getElementById('chartAge'), {
            type: 'bar',
            data: {
                labels: @json($ageDistribution->keys()),
                datasets: [{ label: 'Jumlah', data: @json($ageDistribution->values()), backgroundColor: '#3b82f6' }],
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
        });
    </script>
</x-admin-layout>
