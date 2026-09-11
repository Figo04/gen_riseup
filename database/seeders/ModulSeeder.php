<?php

namespace Database\Seeders;

use App\Models\Modul;
use App\Models\SubBagian;
use Illuminate\Database\Seeder;

class ModulSeeder extends Seeder
{
    /**
     * Seed modul 1 — Kejar Mimpi (sub-bagian per Bab pada dokumen klien).
     */
    public function run(): void
    {
        $modul = Modul::create([
            'nama' => 'Kejar Mimpi',
            'slug' => 'kejar-mimpi',
            'urutan' => 1,
            'cover_image' => 'images/modul-1/cover.jpg',
        ]);

        $subBagian = [
            ['judul' => 'Dua Pintu di Depanmu', 'slug' => 'dua-pintu-di-depanmu'],
            ['judul' => 'Mitos vs Fakta Pernikahan Dini', 'slug' => 'mitos-vs-fakta-pernikahan-dini'],
            ['judul' => 'Circle Sehat vs Circle Mandek', 'slug' => 'circle-sehat-vs-circle-mandek'],
            ['judul' => 'Bangun Fondasi Tubuh & Masa Depanmu', 'slug' => 'bangun-fondasi-tubuh-dan-masa-depanmu'],
            ['judul' => 'Ingat Lagi Mimpimu!', 'slug' => 'ingat-lagi-mimpimu'],
            ['judul' => 'Rencana Aksi: Peta Menuju Mimpimu', 'slug' => 'rencana-aksi-peta-menuju-mimpimu'],
        ];

        foreach ($subBagian as $i => $item) {
            SubBagian::create([
                'modul_id' => $modul->id,
                'judul' => $item['judul'],
                // video_youtube_id belum ada (video belum diupload ke YouTube oleh klien)
                'konten_view' => "modul.kejar-mimpi.{$item['slug']}",
                'urutan' => $i + 1,
                'video_youtube_id' => null,
            ]);
        }
    }
}
