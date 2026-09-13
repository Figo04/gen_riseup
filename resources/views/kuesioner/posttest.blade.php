<x-app-layout>
    @include('kuesioner._form', [
        'judul' => 'Post-test',
        'subjudul' => 'Tahap terakhir. Isi seperti waktu pre-test dulu — jujur aja, ini bukan ujian.',
        'formAction' => route('kuesioner.posttest.store'),
        'tombolLabel' => 'Kirim Post-Test',
        'peringatan' => 'Ini adalah tahap terakhir program. Jawaban post-test bersifat final dan tidak dapat diubah setelah dikirim. Pastikan semua pertanyaan terjawab sebelum menekan "Kirim Post-Test".',
    ])
</x-app-layout>
