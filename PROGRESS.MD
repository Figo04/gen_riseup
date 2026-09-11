## GenResilUp++ — Session Progress Tracker

Tracking eksekusi Build Order (CLAUDE.md §3) per sesi Claude Code.Satu sesi = satu task = fresh context (/clear setiap selesai).Centang status + commit setelah tiap sesi terverifikasi.

Catatan urutan: Sesi 9 (task 7 — komponen visual) sengaja dikerjakansebelum sesi 10–13 (task 6 — seeder per modul), supaya konten tiapmodul langsung pakai komponen dan bisa dites end-to-end (vertical slice).Ini satu-satunya pergeseran urutan dari CLAUDE.md §3.

## Phase 1 — Foundation 

Sesi 1 — Task 1a: Set up project Laravel + Breeze + Tailwind, konfigurasi .env & koneksi DB ✅
Sesi 2 — Task 1b: Migration 11 tabel sesuai schema (PRD §5 Database Structure) ✅
Sesi E1 — Ekstraksi design system: baca design/, buat DESIGN.md + update tailwind config (dikerjakan sebelum sesi 3, karena sesi 3+ mulai menyentuh UI siswa)
Sesi 3 — Task 2a: Student auth (Breeze) + field registrasi tambahan (usia, jenis_kelamin, sekolah, kelas) ✅
Sesi 4 — Task 2b: Admin auth — guard admin, model Admin, login /admin, routes/admin.php ✅

## Phase 2 — Questionnaire (core gating logic)

Sesi 5 — Task 3a: Seed 22 soal pengetahuan (B/S) dari dokumen klien ✅
Sesi 6 — Task 3b: Seed 20 soal sikap (SS/S/TS/STS) + penanda reverse_scored dari dokumen klien ✅
Sesi 7 — Task 4: Form pre-test + material-locking + final submission (validasi backend, no edit after submit) ✅
Sesi 8 — Task 5: Skor otomatis — pengetahuan → % + kategori Baik/Cukup/Kurang; sikap → total (perhatikan reverse_scored) ✅ (dihitung inline di PretestController@store pada Task 4, tidak ada sesi/logic terpisah)

## Phase 3 — Module Content

Sesi 9 — Task 7: Komponen visual reusable (tip box, highlight, checklist, dll.) untuk tab Materials ✅

Catatan keputusan Sesi 9 (dari interview, lihat detail di DESIGN.md dan
catatan deviasi PRD_GenResilUp.md §5):
- `sub_bagian.konten` (longtext) diganti `konten_view` (path view Blade)
  — konten jadi file `.blade.php` asli per sub-bagian, bukan HTML mentah
  di DB. Migration rename sudah jalan; belum ada model/seeder yang pakai.
- Komponen ke-5 untuk tabel Mitos vs Fakta (Modul 1 Bab 2) SENGAJA belum
  dibuat — baru dibuat pas Sesi 10 kalau memang kepake.
- `<x-highlight>` tetap 2 varian warna (mint/peach), tidak ditambah.
- Ikon di komponen konten tetap emoji (bukan icon set); icon set (kalau
  ada) hanya untuk chrome app (nav/card), bukan isi materi.
Sesi 10 — Task 6a: Seeder Modul 1 — Kejar Mimpi (sub-bagian + konten pakai komponen, testable) ✅

Catatan Sesi 10:
- Model baru: `Modul`, `SubBagian` (belum ada sebelumnya, dibutuhkan seeder).
- Komponen ke-5 (tertunda dari Sesi 9) dibuat sekarang: `<x-mitos-fakta>` +
  `<x-mitos-fakta-item>` — dipakai di 4 modul (bukan cuma Modul 1), jadi
  bukan speculative.
- Modul 1 dipecah 6 sub-bagian, 1:1 per Bab pada dokumen klien. Teks
  pembuka modul (sebelum Bab 1) digabung ke sub-bagian Bab 1 karena tabel
  `modul` tidak punya kolom deskripsi terpisah.
- Video belum ada link YouTube dari klien → `video_youtube_id` semua null
  untuk sementara (lihat CLAUDE.md Task 12).
- Gambar dirujuk ke `public/images/modul-1/*.jpg` dengan nama deskriptif;
  file gambar aktual belum ditaruh (belum ada asset dari klien/desainer).
Sesi 11 — Task 6b: Seeder Modul 2 — Investasi Gizi ✅

Catatan Sesi 11:
- `ModulSeeder::run()` diringkas jadi helper privat `seedModul()` (dipakai
  Modul 1 & 2, tidak ubah hasil Modul 1) — supaya Modul 3-4 tinggal
  tambah 1 pemanggilan lagi.
- Modul 2 dipecah 8 sub-bagian, 1:1 per Bab (pola sama Sesi 10). Teks
  pembuka modul digabung ke sub-bagian Bab 1.
- Modul 2 TIDAK punya tabel mitos-vs-fakta → `<x-mitos-fakta>` tidak
  dipakai di sini. 3 tabel data (Zat Gizi, Camilan Sehat, tracker
  mingguan contoh) dirender HTML table statis, bukan komponen baru,
  bukan input tersimpan ke DB (tracker asli = Task 10/Sesi 16).
- Gambar dirujuk ke `public/images/modul-2/*.jpg` (nama deskriptif, file
  asli belum ada). Video masih null (belum ada link YouTube).
Sesi 12 — Task 6c: Seeder Modul 3 — Berpikir Kritis (CRAAP Test, DESC) ✅

Catatan Sesi 12 (dari interview):
- Modul 3 dipecah 7 sub-bagian, 1:1 per Bab (pola sama Sesi 10/11). Bab 6
  (Circle Pendukung & Kebersamaan) jauh lebih pendek dari bab lain tapi
  sengaja TETAP jadi sub-bagian sendiri, bukan digabung — konsisten
  dengan aturan 1:1 per Bab dokumen klien walau isinya singkat.
- Box "Kutipan Penting" (Bab 3) pakai `<x-highlight icon="📌" variant="peach">`
  — belum ada komponen "quote" khusus, dan peach dipilih (bukan mint)
  karena dikonfirmasi tetap.
- Template Surat (Bab 4) pakai `<blockquote>` custom (border+bg mint,
  italic), bukan dibungkus `<x-tip-box>` — dikonfirmasi tetap gaya
  khusus karena konteksnya beda (isian personal, bukan tip/callout).
- Gambar pembuka dokumen (sebelum "Sebelum Mulai") di-drop dari konten
  dan jadi `modul.cover_image`, tidak dirender ulang sebagai figure —
  sama seperti pola Modul 1.
- Nama file gambar deskriptif di `images/modul-3/*.jpg` diperlakukan
  FINAL (bukan tentative) walau file asli belum ada dari klien/desainer.
- `mitos-fakta` TIDAK dipakai — Modul 3 tidak punya pasangan mitos/fakta
  terstruktur. Tidak ada komponen baru dibuat.
- Video masih null (belum ada link YouTube dari klien, sama seperti
  Modul 1 & 2).
Sesi 13 — Task 6d: Seeder Modul 4 — Kenali Tubuhmu ✅

Catatan Sesi 13:
- Modul 4 dipecah 9 sub-bagian, 1:1 per Bab (pola sama Sesi 10-12).
- `<x-mitos-fakta>` + `<x-mitos-fakta-item>` (dibuat Sesi 10) akhirnya
  dipakai lagi di sini (Bab 3 — Body Image), bukan cuma di Modul 1.
- Box peringatan/data (⚠️ Wajib Ingat!, 📊 Data Penting, 🚩 Penting
  Diingat) pakai `<x-highlight>` — variant peach untuk yang bersifat
  peringatan/caution (⚠️, 🚩), variant mint (default) untuk yang
  informasional (📊), konsisten dengan keputusan interview Sesi 12
  soal "Kutipan Penting".
- Gambar cover pertama dokumen (777f7607…) di-drop dari konten, jadi
  `modul.cover_image` — pola sama semua modul sebelumnya.
- Tidak ada komponen baru dibuat. Video tetap null.
- Verifikasi: 9 view berhasil `render()` tanpa error, seeder dijalankan
  ulang lalu duplikat Modul 1-3 yang ikut ke-insert (seeder tidak
  idempotent) dibersihkan manual — hasil akhir 4 modul / 30 sub_bagian.
Sesi 14 — Task 8: Progress tracking per sub-bagian (basis gating refleksi & post-test) ✅

Catatan Sesi 14:
- Model baru: `ProgressModul` (belongsTo User, SubBagian). Ditambah unique
  index (user_id, sub_bagian_id) via migration baru — tabel `progress_modul`
  sudah ada dari Sesi 2 tapi belum punya constraint ini.
- `User::progressModul()` (hasMany) + `User::materiSelesaiSemua()` — basis
  yang akan dipakai Task 9 (gating tab Refleksi) & Task 13 (auto-switch
  post-test).
- Controller baru: `ModulController` (index, show) + `SubBagianController`
  (show, selesai) — belum ada route untuk menampilkan modul/sub-bagian
  sebelum sesi ini, jadi dibuat sekalian supaya progress tracking bisa
  dites end-to-end (pola sama seperti Sesi 9 vertical slice).
- Route `/modul`, `/modul/{modul}`, `/modul/{modul}/{subBagian}` (GET) +
  `/modul/{modul}/{subBagian}/selesai` (POST), semua di belakang
  middleware `pretest.completed` (middleware ini sudah dibuat Sesi 7,
  baru dipakai sekarang).
- View baru: `modul/index.blade.php`, `modul/show.blade.php`,
  `modul/materi.blade.php` (wrapper minimal, belum styling final —
  polish ada di Task 20). Link "Lihat Materi" ditambah ke dashboard.
- Video popup & tab Refleksi SENGAJA belum disentuh (Task 12 & 9).
- Test baru: `tests/Feature/ProgressModulTest.php` (4 test: gating
  pretest.completed, tandai selesai, materiSelesaiSemua threshold,
  404 kalau sub-bagian bukan milik modul di URL). Full suite 38/38 pass.

## Phase 4 — Sub-section Features & Special Screens

Sesi 15 — Task 9: Tab Refleksi (terkunci → submit sekali → read-only, backend enforced) ✅

Catatan Sesi 15 (dari interview sebelum ngoding):
- Refleksi generik berlaku untuk SEMUA 30 sub-bagian (bukan cuma 6 yang
  punya box "Yuk, Refleksi!" eksplisit di konten — box itu tetap tampil
  apa adanya di tab Materi, tidak dipakai jadi sumber prompt form).
- Materi & Refleksi = 2 route terpisah (bukan 1 halaman + JS tab):
  `/modul/{modul}/{subBagian}` dan `.../refleksi`, dihubungkan nav tab
  visual (`modul/_tabs.blade.php`, partial baru dipakai 2 view).
- Akses `/refleksi` sebelum `materi_selesai=true` → redirect ke tab
  Materi + flash message (pola sama `EnsurePretestSubmitted`).
- Setelah submit, halaman refleksi tetap bisa dibuka: textarea disabled
  + jawaban tersimpan tetap tampil (bukan hilang/redirect lain).
- Validasi cuma `required|string`, tanpa minimum karakter (gak ada dasar
  angka dari dokumen klien).
- Model baru: `Refleksi` (belongsTo User, SubBagian) + `User::refleksi()`.
  Migration baru nambah unique index (user_id, sub_bagian_id) ke tabel
  `refleksi` (tabel sudah ada dari Sesi 2, gap sama seperti progress_modul
  sebelum Task 8).
- Controller baru: `RefleksiController` (show, store) — no-edit-after-
  submit di-enforce di backend (re-cek `is_locked` sebelum create, bukan
  cuma sembunyiin form di UI), sesuai CLAUDE.md rule #8.
- Test baru: `tests/Feature/RefleksiTest.php` (5 test, termasuk bukti
  submit kedua kali ditolak backend & tidak mengubah jawaban pertama).
  Full suite 43/43 pass.
Sesi 16 — Task 10: Tracker gizi mingguan (one-time entry, kolom JSON, is_locked) ✅

Catatan Sesi 16 (dari interview sebelum & sesudah ngoding):
- Struktur `data` json: 7 hari (Senin-Minggu) x 4 kebiasaan boolean
  (sayur_buah, protein, air_putih, camilan_sehat) — sama persis dengan
  tabel contoh statis yang sudah ada di sub-bagian "Rencana Aksi
  Kebiasaan Gizi Harianmu" (Modul 2, Sesi 11).
- Tracker diakses lewat link dari dalam sub-bagian tsb, bukan menu
  terpisah — beda dari rencana Kalender Haid (Task 11) yang memang jadi
  menu tersendiri.
- Migration tracker_gizi SUDAH ada dari Sesi 2 (kolom data/is_locked/
  submitted_at persis sesuai PRD §5); ditambah 1 migration baru khusus
  unique index (user_id), pola sama seperti progress_modul & refleksi
  (Sesi 14/15) supaya one-time entry tidak bisa dobel row.
- Model baru: `TrackerGizi` (belongsTo User) + `User::trackerGizi()`
  (hasOne — beda dari refleksi/progress_modul yang hasMany, karena
  tracker ini satu row per user, bukan per sub-bagian).
- Controller baru: `TrackerController@showGizi/@storeGizi`. Nama
  controller ini generik (bukan `TrackerGiziController`) karena PRD §6
  folder structure sudah menetapkan satu `TrackerController` menangani
  gizi mingguan + kalender haid — method kalender haid ditambah nanti
  di Sesi 17, controller yang sama.
- Route `/tracker-gizi` (GET+POST) di grup middleware `pretest.completed`
  yang sama dengan modul/refleksi — TIDAK digating tambahan sampai
  materi_selesai di sub-bagian Rencana Aksi (dikonfirmasi via interview:
  siswa bebas isi tracker kapan saja setelah pretest, tidak harus urut
  ikuti modul, konsisten dengan pola gating fitur lain).
- No-edit-after-submit di-enforce di backend (`storeGizi` cek is_locked
  sebelum create), sesuai CLAUDE.md rule #8 — bukan cuma disable tombol
  di view.
- Tidak ada `$request->validate()` eksplisit — semua field checkbox
  boolean dibaca via `$request->boolean()` (tak-tercentang otomatis
  false), dikonfirmasi cukup karena tidak ada input free-text/format
  yang bisa salah.
- Test baru: `tests/Feature/TrackerGiziTest.php` (3 test: gating
  redirect ke pretest, submit sukses + data ternormalisasi per hari,
  submit kedua kali ditolak & data tidak berubah). Full suite 46/46
  pass.
Sesi 17 — Task 11: Kalender haid (CRUD berkelanjutan, riwayat penuh) ✅

Catatan Sesi 17:
- Migration TIDAK baru — tabel `kalender_haid` sudah ada dari Sesi 2
  dengan kolom persis sesuai PRD §5.
- Model baru: `KalenderHaid` (belongsTo User) + `User::kalenderHaid()`
  hasMany (beda dari refleksi/tracker gizi: ini banyak entri per user,
  bukan one-time).
- Method ditambah ke `TrackerController` yang sama dengan Task 10
  (indexHaid/storeHaid/editHaid/updateHaid/destroyHaid), sesuai PRD §6
  yang menetapkan satu controller untuk gizi + haid.
- Beda pola dari Refleksi/TrackerGizi: kalender haid full CRUD tanpa
  `is_locked` — entri ongoing, boleh diedit/dihapus kapan saja oleh
  pemiliknya (sesuai PRD §3 poin 4 "ongoing... full history retained").
  Ownership dicek manual (`abort_if user_id !== Auth::id()`, 403) di
  tiap method edit/update/destroy karena tidak ada scope query bawaan.
- Route `/kalender-haid` (index/store/edit/update/destroy) di grup
  `pretest.completed` yang sama dengan modul/refleksi/tracker gizi,
  TIDAK terikat urutan modul (PRD: "at any time, not tied to module
  order").
- View baru: `tracker/kalender-haid/index.blade.php` (form tambah +
  tabel riwayat) dan `edit.blade.php`. Link "Kalender Haid" ditambah ke
  dashboard, sebelah "Lihat Materi" (pola sama Sesi 14).
- Test baru: `tests/Feature/KalenderHaidTest.php` (4 test: tambah &
  tampil di riwayat, update entri sendiri, hapus entri sendiri, 403
  kalau coba edit/update/hapus entri user lain). Full suite 50/50 pass.
Sesi 18 — Task 12: Video YouTube popup player di tab Materials ✅

Catatan Sesi 18:
- Reuse `<x-modal>` Breeze (bukan komponen modal baru) — iframe YouTube
  `:src` di-bind ke state `show` milik modal, jadi video berhenti
  otomatis pas popup ditutup, bukan cuma disembunyikan.
- Tanpa autoplay (dikonfirmasi via interview) — video nunggu diklik
  play manual di dalam player, bukan langsung jalan pas popup dibuka.
- Tombol "Tonton Video" fixed di atas kotak konten (`modul/materi.blade.php`),
  tampil hanya kalau `sub_bagian.video_youtube_id` terisi — posisi ini
  dikonfirmasi tetap, bukan komponen dalam alur konten tiap sub-bagian.
- `video_youtube_id` tetap di level sub-bagian (bukan modul) — sesuai
  schema yang sudah ada dari Sesi 9/10, generik untuk bab manapun yang
  klien anggap relevan per modul (bukan mesti bab 1).
- 1 placeholder video_youtube_id ("jNQXAC9IVRw") diisi ke sub-bagian
  pertama Modul 1 untuk testing, ditandai komentar "ganti pas klien
  kasih video asli" — tetap di-commit (dikonfirmasi), 29 sub-bagian
  lain masih null (belum ada link dari klien).
- Test baru: `tests/Feature/VideoPopupTest.php` (2 test: tombol video
  muncul saat video_youtube_id ada, tidak muncul saat kosong). Full
  suite 52/52 pass.

Sesi A1 — Audit Sesi 7-8 (pre-test, material-locking, skoring) ✅

Hasil audit:
- AMAN: skor pengetahuan (%, batas Baik ≥76 / Cukup ≥56 cocok kriteria
  Arikunto), kunci jawaban 22/22 cocok Lampiran B, reverse_scored sikap
  (peta 4-1 / 1-4 benar, 2U per domain cocok kisi-kisi), material-locking
  (semua route materi di dalam `pretest.completed`, termasuk POST selesai),
  transaksi DB (hasil + 42 detail satu `DB::transaction`).
- DIPERBAIKI: `hasil_kuesioner` tidak punya unique index → dua POST
  paralel (double-click/dua tab) bisa bikin 2 baris pre-test. Cek
  `sudahMengisi()` ada di luar transaksi, jadi cuma fast-path.
  Fix: migration unique (user_id, tipe_sesi) — pola sama progress_modul/
  refleksi/tracker_gizi — + `catch (UniqueConstraintViolationException)`
  di `PretestController@store` supaya race berakhir redirect, bukan 500.
- DIPERBAIKI: Sesi 7-8 satu-satunya sesi tanpa test. Ditambah
  `tests/Feature/PretestTest.php` (7 test). Full suite 59/59 pass.
- DITUNDA (dicatat, belum dikerjakan): radio pre-test tanpa `old()` →
  jawaban 42 soal hilang kalau validasi server gagal; `onDelete('cascade')`
  pada `hasil_kuesioner_detail.soal_id` → admin hapus 1 soal di Phase 6
  ikut menghapus jawaban historis siswa (keputusan Phase 6/klien);
  pembagi skor pengetahuan dari `count()` live → DivisionByZeroError
  kalau semua soal pengetahuan dihapus admin; `sudahMengisi()` masih
  hardcode 'pre' (rapikan saat reuse untuk post-test di Sesi 20).

## Phase 5 — Post-test

Sesi 19 — Task 13: Auto-switch menu ke Post-test setelah semua sub-bagian selesai
Sesi 20 — Task 14: Form post-test (reuse struktur Phase 2, tipe_sesi = post, final)

## Phase 6 — Admin Dashboard

Sesi 21 — Task 15: Layout admin (sidebar Data & Konten, gaya SehatEdukasi) + middleware guard admin
Sesi 22 — Task 16a: Dashboard — 4 stat cards + tabel Recent Activity
Sesi 23 — Task 16b: Dashboard — 4 charts (donut status test, donut materi, bar gender, bar usia)
Sesi 24 — Task 17: Kelola Soal — full CRUD kuesioner_soal
Sesi 25 — Task 18: Kelola Materi (view-only) + Data Kalender Haid (view-only); refleksi & tracker tidak pernah di-query
Sesi 26 — Task 19: Export CSV/Excel dari dashboard (pre vs post, exclude refleksi/tracker) + artisan command opsional

## Phase 7 — Polish & QA

Sesi 27 — Task 20: Polish responsive mobile-first (sisi siswa)
Sesi 28 — Task 21: Testing end-to-end manual — siswa & admin, semua alur
Sesi 29+ — Task 22: Revisi preview klien (jumlah sesi terbuka, tergantung catatan klien)
