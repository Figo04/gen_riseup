<?php

namespace Database\Seeders;

use App\Models\KuesionerSoal;
use Illuminate\Database\Seeder;

class KuesionerSoalSeeder extends Seeder
{
    /**
     * Seed 22 soal pengetahuan (B/S) Bagian II kuesioner klien.
     * Sumber: source-content/Kuesioner_Penelitian_GenResilUp__1_.md
     */
    public function run(): void
    {
        $soal = [
            ['Perubahan fisik yang dialami remaja saat pubertas (seperti pertumbuhan payudara atau tinggi badan) dikendalikan oleh hormon.', 'B'],
            ['Semua remaja mengalami kecepatan pertumbuhan fisik saat pubertas yang sama persis satu sama lain.', 'S'],
            ['Salah satu cara menjaga kebersihan saat menstruasi adalah mengganti pembalut setiap 3–4 jam.', 'B'],
            ['Menurut program pemerintah (Aksi Bergizi Kemenkes RI), remaja putri dianjurkan minum Tablet Tambah Darah (TTD) satu tablet setiap hari sepanjang tahun.', 'S'],
            ['Nyeri haid yang sangat hebat hingga mengganggu aktivitas sehari-hari adalah kondisi yang perlu diperiksakan ke tenaga kesehatan.', 'B'],
            ['Jika permintaan seseorang membuat kita tidak nyaman, sebaiknya kita tetap menurutinya saja agar hubungan tidak rusak.', 'S'],
            ['"Hidden hunger" adalah kondisi kekurangan zat gizi mikro meskipun asupan kalori sudah tercukupi.', 'B'],
            ['Vitamin C adalah zat gizi utama yang paling berperan dalam mencegah anemia.', 'S'],
            ['Minum teh atau kopi bersamaan dengan makan besar dapat menghambat penyerapan zat besi dari makanan.', 'B'],
            ['Donat dan es krim termasuk contoh camilan sehat kategori "Brain Boost".', 'S'],
            ['Kekurangan gizi pada remaja putri dapat meningkatkan risiko stunting pada anak yang dilahirkan kelak.', 'B'],
            ['Remaja dianjurkan minum air putih sekitar 2–3 gelas saja per hari.', 'S'],
            ['CRAAP Test adalah metode yang digunakan untuk menilai kredibilitas sebuah sumber informasi.', 'B'],
            ['Teknik DESC digunakan untuk menyampaikan penolakan atau batasan diri dengan cara yang sehat.', 'B'],
            ['Kita sebaiknya langsung mempercayai semua komentar negatif dari orang lain sebagai fakta tentang diri kita.', 'S'],
            ['Sebelum membagikan sebuah informasi di media sosial, sebaiknya kita mengecek dulu kebenaran sumbernya.', 'B'],
            ['Menolak permintaan yang membuat diri kita tidak nyaman adalah tindakan tidak sopan yang sebaiknya dihindari.', 'S'],
            ['Usia reproduksi perempuan yang idealnya sudah matang secara fisik untuk menikah adalah di bawah 15 tahun.', 'S'],
            ['Kehamilan di usia remaja dapat meningkatkan risiko anemia dan kelahiran prematur.', 'B'],
            ['Circle pertemanan yang sehat cenderung memaksa kita mengikuti keputusan besar tertentu, seperti menikah muda.', 'S'],
            ['Kedewasaan sebaiknya diukur dari kematangan berpikir dan kemampuan mengambil keputusan, bukan hanya dari status pernikahan atau usia.', 'B'],
            ['Tekanan dari tetangga atau keluarga seharusnya menjadi alasan utama untuk segera menikah.', 'S'],
        ];

        foreach ($soal as $i => [$pertanyaan, $jawaban]) {
            KuesionerSoal::create([
                'tipe' => 'pengetahuan',
                'pertanyaan' => $pertanyaan,
                'jawaban_benar' => $jawaban,
                'urutan' => $i + 1,
            ]);
        }
    }
}
