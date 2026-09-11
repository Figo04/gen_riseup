## GenResilUp++ — Session Progress Tracker

Tracking eksekusi Build Order (CLAUDE.md §3) per sesi Claude Code.Satu sesi = satu task = fresh context (/clear setiap selesai).Centang status + commit setelah tiap sesi terverifikasi.

Catatan urutan: Sesi 9 (task 7 — komponen visual) sengaja dikerjakansebelum sesi 10–13 (task 6 — seeder per modul), supaya konten tiapmodul langsung pakai komponen dan bisa dites end-to-end (vertical slice).Ini satu-satunya pergeseran urutan dari CLAUDE.md §3.

## Phase 1 — Foundation

Sesi 1 — Task 1a: Set up project Laravel + Breeze + Tailwind, konfigurasi .env & koneksi DB ✅
Sesi 2 — Task 1b: Migration 11 tabel sesuai schema (PRD §5 Database Structure)
Sesi E1 — Ekstraksi design system: baca design/, buat DESIGN.md + update tailwind config (dikerjakan sebelum sesi 3, karena sesi 3+ mulai menyentuh UI siswa)
Sesi 3 — Task 2a: Student auth (Breeze) + field registrasi tambahan (usia, jenis_kelamin, sekolah, kelas)
Sesi 4 — Task 2b: Admin auth — guard admin, model Admin, login /admin, routes/admin.php

## Phase 2 — Questionnaire (core gating logic)

Sesi 5 — Task 3a: Seed 22 soal pengetahuan (B/S) dari dokumen klien
Sesi 6 — Task 3b: Seed 20 soal sikap (SS/S/TS/STS) + penanda reverse_scored dari dokumen klien
Sesi 7 — Task 4: Form pre-test + material-locking + final submission (validasi backend, no edit after submit)
Sesi 8 — Task 5: Skor otomatis — pengetahuan → % + kategori Baik/Cukup/Kurang; sikap → total (perhatikan reverse_scored)

## Phase 3 — Module Content

Sesi 9 — Task 7: Komponen visual reusable (tip box, highlight, checklist, dll.) untuk tab Materials
Sesi 10 — Task 6a: Seeder Modul 1 — Kejar Mimpi (sub-bagian + konten pakai komponen, testable)
Sesi 11 — Task 6b: Seeder Modul 2 — Investasi Gizi
Sesi 12 — Task 6c: Seeder Modul 3 — Berpikir Kritis (CRAAP Test, DESC)
Sesi 13 — Task 6d: Seeder Modul 4 — Kenali Tubuhmu
Sesi 14 — Task 8: Progress tracking per sub-bagian (basis gating refleksi & post-test)

## Phase 4 — Sub-section Features & Special Screens

Sesi 15 — Task 9: Tab Refleksi (terkunci → submit sekali → read-only, backend enforced)
Sesi 16 — Task 10: Tracker gizi mingguan (one-time entry, kolom JSON, is_locked)
Sesi 17 — Task 11: Kalender haid (CRUD berkelanjutan, riwayat penuh)
Sesi 18 — Task 12: Video YouTube popup player di tab Materials

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
