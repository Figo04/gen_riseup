<x-app-layout>
    @include('kuesioner._form', [
        'judul' => 'Pre-test',
        'subjudul' => 'Isi ini sebelum mulai modul. Nggak ada benar-salah yang dinilai, jujur aja ya.',
        'formAction' => route('kuesioner.pretest.store'),
        'tombolLabel' => 'Kirim Pre-Test',
        'peringatan' => 'Jawaban pre-test bersifat final dan tidak dapat diubah setelah dikirim. Pastikan semua pertanyaan terjawab sebelum menekan "Kirim Pre-Test".',
    ])
</x-app-layout>
