<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pre-Test') }}
        </h2>
    </x-slot>

    @include('kuesioner._form', [
        'formAction' => route('kuesioner.pretest.store'),
        'tombolLabel' => 'Kirim Pre-Test',
        'peringatan' => 'Jawaban pre-test bersifat final dan tidak dapat diubah setelah dikirim. Pastikan semua pertanyaan terjawab sebelum menekan "Kirim Pre-Test".',
    ])
</x-app-layout>
