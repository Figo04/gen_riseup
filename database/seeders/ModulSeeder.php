<?php

namespace Database\Seeders;

use App\Models\Modul;
use App\Models\SubBagian;
use Illuminate\Database\Seeder;

class ModulSeeder extends Seeder
{
    /**
     * Seed 4 modul + sub-bagian per Bab pada dokumen klien.
     */
    public function run(): void
    {
        $this->seedModul('Kejar Mimpi', 'kejar-mimpi', 1, 'images/modul-1/cover.jpg', [
            ['judul' => 'Dua Pintu di Depanmu', 'slug' => 'dua-pintu-di-depanmu', 'video' => 'K-jKVzyaIWQ'], // "Fixed chasing dream vs wedding dream"
            ['judul' => 'Mitos vs Fakta Pernikahan Dini', 'slug' => 'mitos-vs-fakta-pernikahan-dini'],
            ['judul' => 'Circle Sehat vs Circle Mandek', 'slug' => 'circle-sehat-vs-circle-mandek'],
            ['judul' => 'Bangun Fondasi Tubuh & Masa Depanmu', 'slug' => 'bangun-fondasi-tubuh-dan-masa-depanmu'],
            ['judul' => 'Ingat Lagi Mimpimu!', 'slug' => 'ingat-lagi-mimpimu'],
            ['judul' => 'Rencana Aksi: Peta Menuju Mimpimu', 'slug' => 'rencana-aksi-peta-menuju-mimpimu'],
        ]);

        $this->seedModul('Investasi Gizi', 'investasi-gizi', 2, 'images/modul-2/cover.jpg', [
            ['judul' => 'Gizi Itu Investasi, Bukan Sekadar Makan', 'slug' => 'gizi-itu-investasi-bukan-sekadar-makan', 'video' => 'Mz-7U4SAln0'], // "nutrition investment"
            ['judul' => 'Fakta & Data: Remaja Indonesia dan Gizi', 'slug' => 'fakta-dan-data-remaja-indonesia-dan-gizi'],
            ['judul' => 'Zat Gizi Kunci yang Wajib Kamu Penuhi', 'slug' => 'zat-gizi-kunci-yang-wajib-kamu-penuhi'],
            ['judul' => 'Apa yang Terjadi Kalau Gizimu Kurang?', 'slug' => 'apa-yang-terjadi-kalau-gizimu-kurang'],
            ['judul' => 'Jangan Lupa Cairan Tubuhmu', 'slug' => 'jangan-lupa-cairan-tubuhmu'],
            ['judul' => 'Strategi Praktis: Maksimalkan Penyerapan Zat Besi', 'slug' => 'strategi-praktis-maksimalkan-penyerapan-zat-besi'],
            ['judul' => 'Camilan Sehat: Brain Boost vs Glow & Grow', 'slug' => 'camilan-sehat-brain-boost-vs-glow-and-grow'],
            ['judul' => 'Rencana Aksi: Kebiasaan Gizi Harianmu', 'slug' => 'rencana-aksi-kebiasaan-gizi-harianmu'],
        ]);

        $this->seedModul('Berpikir Kritis', 'berpikir-kritis', 3, 'images/modul-3/cover.jpg', [
            ['judul' => 'Mengenali Suara-Suara Negatif', 'slug' => 'mengenali-suara-suara-negatif', 'video' => 'FUBBkGTUuck'], // "THINK CRITICALLY, LIVE EMPOWERED"
            ['judul' => 'Kekuatan Berkata "Tidak"', 'slug' => 'kekuatan-berkata-tidak'],
            ['judul' => 'Cerdas di Dunia Digital: Menyaring Informasi', 'slug' => 'cerdas-di-dunia-digital-menyaring-informasi'],
            ['judul' => 'Menyusun "Future-Self Blueprint"', 'slug' => 'menyusun-future-self-blueprint'],
            ['judul' => 'Self-Love: Wujud Nyata Menyayangi Diri Sendiri', 'slug' => 'self-love-wujud-nyata-menyayangi-diri-sendiri'],
            ['judul' => 'Circle Pendukung & Kebersamaan', 'slug' => 'circle-pendukung-dan-kebersamaan'],
            ['judul' => 'Rencana Aksi: START FROM', 'slug' => 'rencana-aksi-start-from'],
        ]);

        $this->seedModul('Kenali Tubuhmu', 'kenali-tubuhmu', 4, 'images/modul-4/cover.jpg', [
            ['judul' => 'Setiap Perubahan Ada Maknanya', 'slug' => 'setiap-perubahan-ada-maknanya', 'video' => 'DYaOoAoFigs'], // "Get to Know Your Body"
            ['judul' => 'Normalisasi Istilah Medis', 'slug' => 'normalisasi-istilah-medis'],
            ['judul' => 'Body Image: Mitos vs Realita Medis', 'slug' => 'body-image-mitos-vs-realita-medis'],
            ['judul' => 'Kenali Siklus Haidmu', 'slug' => 'kenali-siklus-haidmu'],
            ['judul' => 'Red Flag: Kapan Harus Waspada?', 'slug' => 'red-flag-kapan-harus-waspada'],
            ['judul' => 'Rasa Suka & Ketertarikan Romantis', 'slug' => 'rasa-suka-dan-ketertarikan-romantis'],
            ['judul' => 'Berani Menolak & Menjaga Privasi Digital', 'slug' => 'berani-menolak-dan-menjaga-privasi-digital'],
            ['judul' => 'Kesehatan Reproduksi Itu Lebih dari Sekadar Biologis', 'slug' => 'kesehatan-reproduksi-lebih-dari-sekadar-biologis'],
            ['judul' => 'Tips & Strategi Praktis', 'slug' => 'tips-dan-strategi-praktis'],
        ]);
    }

    /**
     * @param  array<int, array{judul: string, slug: string}>  $subBagian
     */
    private function seedModul(string $nama, string $slug, int $urutan, string $coverImage, array $subBagian): void
    {
        $modul = Modul::create([
            'nama' => $nama,
            'slug' => $slug,
            'urutan' => $urutan,
            'cover_image' => $coverImage,
        ]);

        foreach ($subBagian as $i => $item) {
            SubBagian::create([
                'modul_id' => $modul->id,
                'judul' => $item['judul'],
                'konten_view' => "modul.{$slug}.{$item['slug']}",
                'urutan' => $i + 1,
                // video_youtube_id belum ada dari klien untuk sisanya
                'video_youtube_id' => $item['video'] ?? null,
            ]);
        }
    }
}
