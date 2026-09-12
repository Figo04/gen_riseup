<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post-Test') }}
        </h2>
    </x-slot>

    @include('kuesioner._form', [
        'formAction' => route('kuesioner.posttest.store'),
        'tombolLabel' => 'Kirim Post-Test',
        'peringatan' => 'Ini adalah tahap terakhir program. Jawaban post-test bersifat final dan tidak dapat diubah setelah dikirim. Pastikan semua pertanyaan terjawab sebelum menekan "Kirim Post-Test".',
    ])
</x-app-layout>
