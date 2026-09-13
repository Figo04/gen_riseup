<?php

/*
 * Isi lembar refleksi per modul, disalin dari GenResilUp++_Modul_Resiliensi_Remaja.pdf
 * (hal. 13 / 23 / 31 / 40). Satu lembar per modul, muncul di sub-bagian terakhir.
 *
 * tabel   → worksheet yang diisi langsung di halaman refleksi.
 * tautan  → worksheet yang sudah punya halaman sendiri, cukup ditautkan.
 */
return [

    'kenali-tubuhmu' => [
        'judul' => 'Mengenal Tubuh & Batasanku',
        'pertanyaan' => [
            'Perubahan fisik apa yang paling sering membuatmu insecure? Setelah membaca bagian ini, apa yang berubah dari cara pandangmu?',
            'Tuliskan 1 kalimat penolakan versimu sendiri, yang bisa kamu pakai kalau ada yang memintamu melakukan sesuatu yang tidak nyaman.',
        ],
        'tautan' => [
            'route' => 'kalender-haid.index',
            'judul' => 'Kalender Siklus Haid',
            'teks' => 'Catat tanggal mulai haid setiap bulan untuk mengenali pola siklusmu sendiri.',
            'label' => '🩸 Buka Kalender Haid',
        ],
    ],

    'investasi-gizi' => [
        'judul' => 'Kebiasaan Gizi Harianku',
        'pertanyaan' => [
            'Dari semua zat gizi yang dibahas, mana yang menurutmu paling jarang kamu penuhi selama ini?',
            'Camilan apa yang paling sering kamu makan? Termasuk kategori Brain Boost, Glow & Grow, atau justru "energi kosong"?',
        ],
        'tautan' => [
            'route' => 'tracker-gizi.show',
            'judul' => 'Pantau Kebiasaan Makanmu Seminggu',
            'teks' => 'Centang kebiasaan makanmu selama seminggu: sayur & buah, sumber protein, air putih, dan camilan sehat.',
            'label' => '📋 Isi Tracker Gizi Mingguan',
        ],
    ],

    'berpikir-kritis' => [
        'judul' => 'Rencana Aksi START FROM',
        'pengantar' => 'Suratku untuk Diri Sendiri di Masa Depan.',
        'pertanyaan' => [
            'Halo [namamu di masa depan], hari ini aku memutuskan untuk…',
            'Aku tahu ini nggak akan selalu mudah, terutama saat…',
            'Tapi aku janji akan…',
        ],
        'tabel' => [
            'judul' => 'Worksheet: Rencana Aksi Pribadi',
            'kolom' => ['Area', 'Tantangan Saat Ini', 'Langkah yang Akan Kucoba'],
            'contoh' => ['Contoh: Menolak ajakan', 'Susah bilang "tidak" ke teman', 'Latihan teknik DESC minggu ini'],
            'baris' => 3,
        ],
    ],

    'kejar-mimpi' => [
        'judul' => 'Peta Menuju Mimpimu',
        'pengantar' => 'Halaman ini milikmu sepenuhnya. Tidak ada jawaban benar atau salah — tulis saja apa yang jujur menurutmu.',
        'pertanyaan' => [
            'Sebutkan 1 mimpi/cita-cita yang paling ingin kamu capai sebelum usia 25 tahun.',
            'Adakah tekanan (dari keluarga, circle, atau media sosial) yang selama ini memengaruhi keputusanmu? Ceritakan.',
            'Siapa 1–2 orang dalam circle-mu yang benar-benar mendukung mimpimu?',
        ],
        'tabel' => [
            'judul' => 'Peta Rencana Aksi',
            'kolom' => ['Cita-Cita / Tujuan', 'Langkah Kecil (Bulan Ini)', 'Yang Kubutuhkan', 'Siapa yang Bisa Bantu'],
            'contoh' => ['Contoh: Kuliah kedokteran', 'Perbaiki nilai Biologi & Kimia', 'Belajar rutin, buku latihan soal', 'Guru les, kakak kelas'],
            'baris' => 3,
        ],
    ],

];
