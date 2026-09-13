# Checklist Manual Sebelum Demo

Yang **sudah** dijamin otomatis oleh `php artisan test` (98 tes, termasuk
`tests/Feature/AlurLengkapTest.php` yang menjalankan satu perjalanan utuh):
semua gating, skoring, penguncian submit, batas akses admin, dan isi export.

Daftar di bawah ini **hanya** berisi yang tidak bisa dibuktikan test, karena
butuh browser sungguhan, mata, atau jaringan.

---

## 0. Sebelum apa pun — nyalakan Laragon

```
Laragon → Start All        # MySQL harus hijau
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

`migrate:fresh` **menghapus seluruh data**. Jalankan hanya kalau kamu memang
mau mulai dari kosong untuk demo.

Akun setelah seed:
- Admin: `admin@genresilup.test` / `password` di `/admin`
- Siswa: daftar baru lewat `/register` (lebih meyakinkan saat demo daripada
  akun contoh `test@example.com` yang belum punya usia/sekolah)

---

## 1. Hal yang butuh mata (tidak bisa dites otomatis)

- [ ] **Video YouTube benar-benar diputar** di popup, bukan pindah halaman.
      Satu-satunya yang punya video: `/modul/1/1` — Kejar Mimpi → "Dua Pintu
      di Depanmu". Tombolnya sudah terbukti tampil di server asli.
      ⚠️ Video ID-nya masih **placeholder** (`jNQXAC9IVRw` = "Me at the zoo"),
      bukan video klien. Ganti dulu kalau akan tampil di presentasi:
      `php artisan tinker` → `App\Models\SubBagian::find(1)->update(['video_youtube_id' => 'ID_BARU']);`
- [ ] **Butuh internet.** Font (fonts.bunny.net) dan video YouTube diambil
      online. Kalau ruangan presentasinya tanpa wifi, halaman tetap jalan tapi
      font berubah dan video mati.
- [ ] **Interaksi Alpine** — semuanya JavaScript, test tidak menyentuhnya:
  - [ ] Kalender Haid: tombol "Catat haid bulan ini" membuka form, tombol ×
        menutupnya
  - [ ] Profil: tombol "Ubah" menukar tampilan baca ↔ form, "Batal" kembali
  - [ ] Kuesioner: pil jawaban berubah hijau saat dipilih
  - [ ] Tracker Gizi: pil kebiasaan berubah hijau saat dicentang
- [ ] **Lebar layar 360px dan 768px** (devtools). Tidak boleh ada scroll
      horizontal di halaman siswa mana pun.
- [ ] **File export dibuka di Excel.** `/admin/export?format=excel` → kolomnya
      harus terpisah rapi, bukan menumpuk di kolom A.
- [ ] **Grafik admin tampil** (donut + bar). Chart.js/ApexCharts butuh data;
      dengan 1 siswa saja grafiknya sepi — isi 2–3 siswa contoh kalau mau
      terlihat berisi saat presentasi.

## 2. Alur demo yang disarankan (±8 menit)

1. `/register` — daftar sebagai siswa baru di depan penonton
2. Home — tunjukkan kartu "Pre-test" bergaris putus-putus, coba klik tab
   **Materi** → ditolak, dilempar ke pre-test (ini bukti gating-nya nyata)
3. Isi pre-test → kembali ke Home, kartu berubah jadi "Sudah kamu isi"
4. Materi → satu modul → satu sub-bagian: tunjukkan tab **Refleksi masih
   bergembok**, tekan "Tandai sudah dibaca", refleksi terbuka
5. Kalender Haid — tambah 1 entri, tunjukkan tanda pink muncul di grid
6. Profil — tunjukkan data pribadi + progress
7. Logout → `/admin` → dashboard, Kelola Soal, lalu Export

> Post-test butuh **semua** sub-bagian di 4 modul selesai (total banyak).
> Jangan coba menuntaskannya live. Kalau perlu menunjukkan post-test,
> siapkan satu akun siswa yang sudah tuntas **sebelum** presentasi.

## 3. Kalau ditanya, ini jawaban jujurnya

- **Gembok pada daftar modul** = penanda "belum selesai", bukan kunci. Semua
  modul terbuka setelah pre-test (PRD §3.1). Mockup klien menulis "Terkunci",
  tapi PRD yang menang.
- **"6 menit baca"** di mockup tidak dipasang — datanya tidak ada di database
  dan tidak mau dikarang.
- **Sisi admin belum direstyle** — belum ada mockup untuk admin
  (`design/admin/` masih kosong). Ini sudah sesuai PRD §7 yang membolehkan
  admin berorientasi desktop.
- **Refleksi & Tracker Gizi tidak pernah terlihat admin** di mana pun —
  ini dijaga di level query, dan ada tesnya.
