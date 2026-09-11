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
            ['judul' => 'Dua Pintu di Depanmu', 'slug' => 'dua-pintu-di-depanmu'],
            ['judul' => 'Mitos vs Fakta Pernikahan Dini', 'slug' => 'mitos-vs-fakta-pernikahan-dini'],
            ['judul' => 'Circle Sehat vs Circle Mandek', 'slug' => 'circle-sehat-vs-circle-mandek'],
            ['judul' => 'Bangun Fondasi Tubuh & Masa Depanmu', 'slug' => 'bangun-fondasi-tubuh-dan-masa-depanmu'],
            ['judul' => 'Ingat Lagi Mimpimu!', 'slug' => 'ingat-lagi-mimpimu'],
            ['judul' => 'Rencana Aksi: Peta Menuju Mimpimu', 'slug' => 'rencana-aksi-peta-menuju-mimpimu'],
        ]);

        $this->seedModul('Investasi Gizi', 'investasi-gizi', 2, 'images/modul-2/cover.jpg', [
            ['judul' => 'Gizi Itu Investasi, Bukan Sekadar Makan', 'slug' => 'gizi-itu-investasi-bukan-sekadar-makan'],
            ['judul' => 'Fakta & Data: Remaja Indonesia dan Gizi', 'slug' => 'fakta-dan-data-remaja-indonesia-dan-gizi'],
            ['judul' => 'Zat Gizi Kunci yang Wajib Kamu Penuhi', 'slug' => 'zat-gizi-kunci-yang-wajib-kamu-penuhi'],
            ['judul' => 'Apa yang Terjadi Kalau Gizimu Kurang?', 'slug' => 'apa-yang-terjadi-kalau-gizimu-kurang'],
            ['judul' => 'Jangan Lupa Cairan Tubuhmu', 'slug' => 'jangan-lupa-cairan-tubuhmu'],
            ['judul' => 'Strategi Praktis: Maksimalkan Penyerapan Zat Besi', 'slug' => 'strategi-praktis-maksimalkan-penyerapan-zat-besi'],
            ['judul' => 'Camilan Sehat: Brain Boost vs Glow & Grow', 'slug' => 'camilan-sehat-brain-boost-vs-glow-and-grow'],
            ['judul' => 'Rencana Aksi: Kebiasaan Gizi Harianmu', 'slug' => 'rencana-aksi-kebiasaan-gizi-harianmu'],
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
                // video_youtube_id belum ada (video belum diupload ke YouTube oleh klien)
                'konten_view' => "modul.{$slug}.{$item['slug']}",
                'urutan' => $i + 1,
                'video_youtube_id' => null,
            ]);
        }
    }
}
