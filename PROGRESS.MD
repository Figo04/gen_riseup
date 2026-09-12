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

Sesi 19-20 — Task 14 lalu Task 13: Form post-test (reuse struktur Phase 2) +
auto-switch menu ke Post-test ✅

Catatan Sesi 19-20 (urutan ditukar dari CLAUDE.md §3, dikonfirmasi via
interview — Task 14 duluan karena menu switch di Task 13 butuh route
post-test sudah ada supaya tidak jadi link mati/stub sementara):
- Deviasi struktur folder DIPERBAIKI sekalian: `PretestController` +
  `resources/views/pretest/create.blade.php` (dari Sesi 7, tidak pernah
  dicatat sebagai deviasi resmi) di-rename jadi `KuesionerController` +
  `resources/views/kuesioner/{pretest,posttest}.blade.php` — sesuai PRD
  §6 yang dari awal menetapkan satu controller kuesioner untuk pre+post.
  File lama dihapus (dikonfirmasi dulu sebelum dihapus, sesuai CLAUDE.md
  rule #1). Route name ganti `pretest.create`/`pretest.store` →
  `kuesioner.pretest.create`/`kuesioner.pretest.store`.
- View pre-test & post-test berbagi 1 partial (`kuesioner/_form.blade.php`)
  — markup form identik (22+20 soal, radio B/S & SS/S/TS/STS), cuma beda
  judul/label tombol/teks peringatan final.
- `KuesionerController` generik: 4 method publik tipis (createPre/storePre/
  createPost/storePost) delegasi ke 2 method private (`create`/`store`)
  yang di-parameter-kan `$tipeSesi`. Logic skoring (pengetahuan % + kategori
  Baik/Cukup/Kurang, sikap total dengan reverse_scored) sama persis dengan
  Sesi 7-8, tidak diubah — cuma dipakai ulang untuk 'post'.
- Sekalian membereskan item DITUNDA dari Sesi A1: `sudahMengisi()` sudah
  tidak hardcode 'pre' lagi (terima parameter `$tipeSesi`), karena memang
  dipakai bareng oleh pre & post sekarang.
- Middleware baru: `EnsureModulSelesai` (alias `modul.selesai`), pola sama
  `EnsurePretestSubmitted` — redirect ke dashboard + flash message kalau
  `User::materiSelesaiSemua()` belum true. Route `/posttest` (GET+POST)
  digating `pretest.completed` + `modul.selesai` sekaligus.
- Menu nav (`navigation.blade.php`, desktop & responsive): 1 item dinamis
  `@if (materiSelesaiSemua()) Post-Test @else Pre-Test @endif` — bukan 2
  item terpisah, sesuai PRD §4.1 poin 3 (satu menu dinamis, bukan
  sequential unlock).
- Kondisi PRD literal dipertahankan: kalau pretest sudah diisi tapi materi
  belum kelar semua, menu tetap tampil "Pre-Test" (klik → redirect balik
  ke dashboard dengan pesan "sudah pernah diisi"), bukan "Post-Test
  (terkunci)" — tidak diminta lain jadi ikut PRD apa adanya.
- Test baru: `tests/Feature/PosttestTest.php` (4 test: post-test terkunci
  sebelum materi kelar semua, bisa diakses+diskor setelah kelar, submit
  kedua ditolak backend, pre & post tidak saling menghalangi karena
  tipe_sesi beda). Route name lama diupdate di `PretestTest.php`,
  `TrackerGiziTest.php`, `ProgressModulTest.php` (masih dipakai buat
  assert redirect ke pretest). Full suite 63/63 pass.

## Phase 6 — Admin Dashboard

Sesi 21 — Task 15: Layout admin (sidebar Data & Konten, gaya SehatEdukasi) + middleware guard admin ✅

Catatan Sesi 21:
- Guard admin TIDAK butuh middleware baru — `auth:admin`/`guest:admin` +
  `redirectGuestsTo` di `bootstrap/app.php` sudah cukup dari Sesi 4,
  sudah dites `AdminAuthenticationTest` yang sudah ada.
- `resources/views/layouts/admin.blade.php` + `app/View/Components/AdminLayout.php`
  (class component, pola sama `GuestLayout`/`AppLayout` bawaan Breeze di
  proyek ini) supaya `<x-admin-layout>` bisa dipakai.
- Sidebar 2 grup (Data: Dashboard/Responden/Hasil Test, Konten: Kelola
  Soal/Kelola Materi/Data Kalender Haid). Menu selain Dashboard sengaja
  placeholder non-link (`<x-admin-nav-link disabled>`, dikonfirmasi user)
  karena rute/controller-nya belum ada sampai Sesi 22-26.
- Test baru: `tests/Feature/Admin/AdminLayoutTest.php` (2 test). Full
  suite 65/65 pass.
Sesi 22 — Task 16a: Dashboard — 4 stat cards + tabel Recent Activity ✅

Catatan Sesi 22 (dari interview sebelum ngoding):
- Recent Activity = submit kuesioner saja (`hasil_kuesioner`), tidak
  digabung registrasi user atau progress modul; 10 baris terbaru
  (`orderBy submitted_at desc limit 10`), tanpa paginasi/link "lihat
  semua" (menyusul Sesi 24 kalau relevan).
- Total Respondents = `User::count()` (semua siswa terdaftar, bukan
  cuma yang sudah isi kuesioner).
- Pre-Test/Post-Test Completed = `count()` per `tipe_sesi` (aman tanpa
  `distinct()` karena unique(user_id, tipe_sesi) sudah ada dari Sesi A1).
- Pre+Post Complete = user dengan `hasil_kuesioner` untuk KEDUA
  `tipe_sesi` (whereHas ganda).
- "Real-time" (PRD §4.2) dibaca sebagai query fresh tiap request, bukan
  polling/websocket — konsisten pola proyek ini.
- `App\Http\Controllers\Admin\DashboardController` baru, route
  `admin.dashboard` pindah dari closure ke controller ini.
- Test baru: `tests/Feature/Admin/AdminDashboardTest.php` (2 test: angka
  stat card benar, recent activity 10 terbaru terurut desc). Full suite
  67/67 pass.
Sesi 23 — Task 16b: Dashboard — 4 charts (donut status test, donut materi, bar gender, bar usia) ✅

Catatan Sesi 23:
- Chart.js via CDN (`chart.umd.min.js`) di `layouts/admin.blade.php`, bukan
  `npm install` — cuma 1 halaman admin, tidak perlu ikut bundle Vite sisi
  siswa.
- Donut Status Test = 3 bucket (Belum Pre-Test / Pre-Test Saja / Pre+Post
  Selesai) dihitung dari angka yang SUDAH ada di `DashboardController`
  Sesi 22 (totalRespondents/preCompleted/prePostComplete) — tidak nambah
  query baru.
- Donut Progress Materi = query baru: `whereHas('progressModul', ...,
  '>=', $totalSubBagian)`, pola sama `User::materiSelesaiSemua()` (Sesi
  14) versi agregat di level query builder.
- Bar Gender & Usia = `groupBy` langsung dari kolom `users.jenis_kelamin`
  / `users.usia`.
- Data dikirim ke view sebagai array/collection PHP → `@json()` di
  `<script>` inline, bukan endpoint AJAX terpisah — konsisten pola
  server-rendered Blade proyek ini ("real-time" = fresh tiap request,
  sama seperti keputusan Sesi 22).
- Test baru ditambah ke `AdminDashboardTest.php` (bukan file terpisah):
  3 user dengan kombinasi status test/materi/gender/usia berbeda,
  assert keempat aggregate benar. Full suite 68/68 pass.
Sesi 24 — Task 17: Kelola Soal — full CRUD kuesioner_soal ✅

Keputusan interview sebelum ngoding Sesi 24:
- Hapus soal = soft delete via kolom baru `is_aktif` (boolean, default
  true), BUKAN `deleted_at`/SoftDeletes trait. Tombol Hapus di admin set
  `is_aktif=false`. FK `hasil_kuesioner_detail.soal_id` tidak pernah
  putus → jawaban historis siswa aman permanen. Form pre/post-test &
  seeder query cuma `where('is_aktif', true)`. Ini nutup item DITUNDA
  dari audit Sesi A1 (onDelete cascade berisiko hapus data riset).
- Edit `jawaban_benar` TIDAK recompute skor historis — `hasil_kuesioner`
  yang sudah tersimpan tetap seperti hasil hitung saat submit dulu; kunci
  jawaban baru cuma berlaku ke submission berikutnya.
- Kolom `urutan` otomatis (max+1 per `tipe` saat insert soal baru) —
  tidak ada input manual urutan di form CRUD, tidak ada reorder/drag.
- Jumlah soal per tipe BEBAS, tidak dikunci di 22 (pengetahuan) / 20
  (sikap) — pembagi skor sudah `count()` live sejak Sesi A1, sesuai PRD
  "client maintains questions independently going forward".
- Form tambah/edit dinamis by `tipe`: pilih tipe dulu, lalu field
  `jawaban_benar` (kalau pengetahuan) atau `reverse_scored` (kalau
  sikap) muncul sesuai — field yang tidak relevan disembunyikan/dipaksa
  default, bukan sama-sama tampil terus.
- Soal nonaktif (`is_aktif=false`) hilang dari list index admin
  (dikonfirmasi via interview — tidak ada tab "Nonaktif"/tombol
  Aktifkan; kalau salah hapus, perlu akses DB untuk balikin).

Catatan implementasi Sesi 24:
- Migration baru `add_is_aktif_to_kuesioner_soal_table` (boolean,
  default true, after `urutan`). Model `KuesionerSoal` update
  fillable+cast.
- `KuesionerController@create`/`@store` (Task 13/14, Sesi 7/19-20)
  ditambah `where('is_aktif', true)` supaya soal yang dihapus admin
  tidak lagi muncul di pre/post-test — pembagi skor pengetahuan ikut
  otomatis karena `count()` live sejak Sesi A1.
- Controller baru `Admin\SoalController` (index/create/store/edit/
  update/destroy, sesuai path PRD §6) + route `Route::resource('soal', ...)`
  di `routes/admin.php`. `destroy` = `update(['is_aktif' => false])`,
  bukan `delete()` — FK `hasil_kuesioner_detail.soal_id` (onDelete
  cascade) tidak pernah tersentuh, jawaban historis siswa aman
  permanen. Ini menutup item DITUNDA dari audit Sesi A1.
- Validasi dinamis 1 method privat `validated()`: rule `tipe` cuma
  ada di form create (fixed/tidak bisa diubah di edit); `jawaban_benar`
  required kalau pengetahuan (else `prohibited`), `reverse_scored`
  boolean kalau sikap (else `prohibited`).
- Edit `jawaban_benar`/`reverse_scored` TIDAK recompute skor
  `hasil_kuesioner` yang sudah tersimpan — kunci baru cuma berlaku ke
  submission berikutnya, sesuai keputusan interview.
- `urutan` otomatis `max('urutan') per tipe + 1` saat create, tidak
  ada input manual/reorder di form.
- Item DITUNDA (dicatat, belum dikerjakan): guard `DivisionByZeroError`
  kalau admin menghapus SEMUA soal pengetahuan (pembagi skor jadi 0) —
  belum ada kejadian nyata, ditunda sampai relevan.
- Sidebar admin: link "Kelola Soal" (`layouts/admin.blade.php`) dari
  placeholder disabled jadi link aktif ke `admin.soal.index`.
- View baru: `admin/soal/{index,create,edit}.blade.php`. Index
  dipisah 2 tabel (Pengetahuan/Sikap). Form create/edit toggle field
  by tipe pakai Alpine `x-show` (create) / kondisi Blade tetap (edit,
  karena tipe tidak bisa diubah).
- Test baru: `tests/Feature/Admin/AdminSoalTest.php` (6 test: create
  pengetahuan dengan urutan auto-increment, create sikap dengan
  reverse_scored, update, edit jawaban_benar tidak mengubah skor
  historis, delete = soft-delete + detail histori tetap utuh, soal
  nonaktif hilang dari index admin & pretest). Full suite 74/74 pass.

Post-check Sesi 24 (ditemukan & langsung diperbaiki, belum sempat kepakai
manual sebelum ke-commit): 2 bug bikin form Tambah Soal tidak bisa
dipakai — `x-data="{ tipe: old(...) }"` di `create.blade.php` bocor
mentah (di luar `{{ }}`), Alpine error, field jawaban_benar/reverse_scored
gak pernah muncul; rule `prohibited` menolak field tersembunyi (x-show
cuma CSS) yang tetap ikut ter-POST, jadi tambah soal sikap selalu gagal.
Fix + 2 regression test baru, full suite 76/76 pass, commit terpisah.
Sesi 25 — Task 18: Kelola Materi (view-only) + Data Kalender Haid (view-only); refleksi & tracker tidak pernah di-query ✅

Catatan Sesi 25:
- Kelola Materi: 3 route GET-only (bukan `Route::resource`, supaya tidak ada
  route tulis sama sekali) — index (daftar 4 modul + jumlah sub-bagian) →
  show (daftar sub-bagian per modul) → sub-bagian (render `konten_view` yang
  sama persis dipakai sisi siswa, lewat `@include`). Tidak ada tombol
  "Tandai Selesai"/video popup di view admin — murni preview konten.
  Controller baru `Admin\MateriController` (index/show/subBagian).
- Data Kalender Haid: 1 route GET-only, tabel flat SEMUA entri semua siswa
  (`with('user')->orderByDesc('tanggal_mulai')`) — tidak per-siswa/drill-down
  (dikonfirmasi cukup, PRD cuma minta "view-only" + riwayat penuh, tidak
  minta filter/agregat per siswa). Tidak ada tombol edit/hapus. Controller
  baru `Admin\KalenderHaidController` (index saja).
- Refleksi & TrackerGizi TIDAK PERNAH di-query di kedua controller (CLAUDE.md
  rule #9 + PRD §4.3/§7) — dites eksplisit di `AdminKalenderHaidTest`
  (jawaban refleksi rahasia tidak pernah muncul di halaman kalender haid).
- Sidebar admin: 2 link "Kelola Materi"/"Data Kalender Haid" dari placeholder
  disabled jadi aktif (pola sama Sesi 24).
- Test baru: `tests/Feature/Admin/AdminMateriTest.php` (4 test) +
  `AdminKalenderHaidTest.php` (3 test). Full suite 83/83 pass.
- Gap dicatat (belum dikerjakan, bukan scope sesi ini): menu "Responden" &
  "Hasil Test" di sidebar admin (PRD §4.2) belum punya sesi build sama
  sekali di tracker ini — masih placeholder disabled.
Sesi 26 — Task 19: Export CSV/Excel dari dashboard (pre vs post, exclude refleksi/tracker) ✅

Catatan Sesi 26:
- 1 route GET `admin/export` → `Admin\ExportController` (invokable). Output
  1 baris = 1 siswa, 14 kolom: identitas (nama, email, usia, jenis_kelamin,
  sekolah, kelas) + 4 kolom skor pre + 4 kolom skor post sejajar di baris yang
  sama, jadi pre vs post langsung kebandingkan tanpa pivot manual di Excel.
- TANPA dependency baru (`maatwebsite/excel` tidak diinstall): `fputcsv` ke
  `php://output` lewat `response()->streamDownload()`. Tombol "Export Excel"
  pakai route yang sama + `?format=excel` → delimiter `;` + BOM UTF-8 supaya
  langsung kebuka rapi di Excel locale Indonesia; ekstensi tetap `.csv`.
  Kalau klien nanti wajib `.xlsx` asli, baru install maatwebsite.
- Siswa yang belum pre/post tetap ikut terekspor dengan sel skor kosong
  (`$pre?->...`), biar jumlah baris cocok dengan stat card Total Respondents.
- Refleksi & TrackerGizi TIDAK PERNAH di-query (PRD §4.3 + CLAUDE.md rule #9)
  — sumber data cuma `users` + `hasil_kuesioner` (`with('hasilKuesioner')`,
  no N+1). Dites eksplisit: jawaban refleksi & key tracker gizi tidak pernah
  muncul di CSV.
- Detail per-jawaban-soal (`hasil_kuesioner_detail`) TIDAK diekspor — PRD §4.2
  cuma minta "pre vs post scored results".
- Tombol Export CSV & Export Excel ditaruh di header `admin/dashboard.blade.php`
  (sebaris dengan sapaan "Selamat datang"), sesuai PRD §4.2.
- Test baru: `tests/Feature/Admin/AdminExportTest.php` (6 test: guest & siswa
  ditolak, pre+post satu baris, siswa tanpa submission tetap terekspor kosong,
  refleksi/tracker tidak pernah bocor, format excel pakai BOM + `;`).
  Full suite 89/89 pass.
- DI-SKIP (sengaja, PRD §6 bilang "optional"): artisan command
  `ExportHasilKuesioner.php` — dashboard sudah cukup, tambah kalau klien minta
  export terjadwal/scripted. Juga di-skip: filter/date-range di export (selalu
  semua siswa).
- Gap masih terbuka (bukan scope sesi ini, sama seperti catatan Sesi 25): menu
  "Responden" & "Hasil Test" di sidebar admin masih placeholder disabled.

## Phase 7 — Polish & QA

Sesi 27 — Task 20: Polish responsive mobile-first (sisi siswa)
Sesi 28 — Task 21: Testing end-to-end manual — siswa & admin, semua alur
Sesi 29+ — Task 22: Revisi preview klien (jumlah sesi terbuka, tergantung catatan klien)
