Split note: This file holds the full requirement details (background,roles & access, scope, user flow, database schema, folder structure,non-functional requirements). Claude Code reads this file on-demandwhen a task needs them (e.g. "baca PRD_GenResilUp.md dulu" before writingmigrations). The always-loaded session brief (tech stack, build order,run instructions, agent rules, completion criteria) lives inCLAUDE.md.

Merge note (from v3.0): This document reconciles two prior files.Where the two conflicted, the updated PRD (v2.0) was treated asauthoritative: the admin dashboard is in scope, and the menstrualcalendar is an ongoing/multi-entry feature, not a one-time entry.Everything from the older operating brief that didn't conflict (folderstructure, run instructions, agent rules, completion criteria) has beenkept and updated to match.

## 1. Project Overview

GenResilUp++ is a web-based teen resilience education platform built for
a lecturer's (dosen) research project — the developer's third project of
this kind, following a previous anemia-education project. It delivers
four educational modules to students aged 13–18:

- **Kejar Mimpi** — life direction / avoiding early-marriage pressure
- **Investasi Gizi** — balanced nutrition
- **Berpikir Kritis** — critical thinking (CRAAP Test, DESC technique)
- **Kenali Tubuhmu** — puberty, menstrual cycle, personal boundaries

All content (module text, images, 4 videos, and the research
questionnaire with the official scoring key) is supplied by the client
and hardcoded directly by the developer (not via a CMS) — redesigned
into an engaging layout per sub-section. Development scope is the
platform itself, not the content.

**Core student flow:** student registers → must complete a pre-test
before any module unlocks → reads through all 4 modules (each with a
reflection sheet, plus a one-time nutrition tracker in the Gizi module)
→ post-test auto-unlocks once all modules are marked complete → student
submits post-test (final, no retakes). Menstrual cycle logging is a
separate, ongoing screen the student can add to at any time.

**Core admin flow:** the client (dosen) now has their own login,
separate from students, with a statistics dashboard, question (CRUD)
management, and CSV/Excel export — so they're no longer dependent on the
developer for every data pull.

Full functional scope, data model, and phased build plan live in §8–9
below — this document is the single operating brief for whoever (human
or agent) is actively writing code.

---

## 2. Roles & Access

| Role                        | Description             | Access                                                                             |
| --------------------------- | ----------------------- | ---------------------------------------------------------------------------------- |
| **Student**                 | Primary adolescent user | Web (mobile-first), email + password login, 5 main screens                         |
| **Admin (client/lecturer)** | Research owner          | Separate login (`/admin`), statistics dashboard, question management, data viewing |

The admin has their own login account (dashboard was added to scope in
PRD v2.0, superseding an earlier "no dashboard, manual export only"
plan). Dashboard design reference follows the admin panel pattern of
SehatEdukasi (developer's previous project).

---

## 3. Scope

### 4.1 Student Side — 4 Main Screens

1. **Materials** — list of 4 modules → each sub-section has 2 tabs:
    - **Materials** tab: redesigned content (not plain text), including
      an embedded YouTube video (popup player)
    - **Reflection** tab: locked until the Materials tab is marked
      complete; once submitted becomes **read-only** (one-time entry)
    - The Nutrition module also has a weekly eating-habit tracker
      (one-time entry, saved like the reflection)
2. **Pre-test / Post-test** — a single dynamic menu:
    - Before all modules are completed → shows the Pre-test form
    - After all modules are marked read → automatically switches to the
      Post-test
    - Final submission, cannot be edited afterward
3. **Menstrual Calendar** — a standalone screen (separate from
   Materials), **ongoing**: students can keep adding new entries every
   month (start date, end/duration, symptom notes); full history is
   retained, not limited to a single entry
4. **Profile** — student's personal data

> **Deviation note (decided Sesi 27):** the original spec also listed a
> standalone "Home" summary/landing screen. Dropped intentionally — the
> `/dashboard` route stays a thin router (links into Materials/Kalender
> Haid via nav), no separate landing page is built.

### 4.2 Admin (Client) Side

- **Admin login** separate from student login
- **Dashboard**: stat cards (Total Respondents, Pre-Test Completed,
  Post-Test Completed, Pre+Post Complete), donut chart for Test
  Completion Status, donut chart for Material Completion, bar chart for
  Gender Distribution, bar chart for Age Distribution (13–18), Recent
  Activity table, Export CSV & Export Excel buttons
- **Respondents menu** — list of registered students
- **Test Results menu** — pre/post-test results per student (already
  scored: knowledge → Good/Fair/Poor, attitude → total score)
- **Manage Questions menu** — **full CRUD** (add/edit/delete knowledge &
  attitude questions) — client maintains questions independently going
  forward
- **Manage Materials menu** — **view-only** (materials remain hardcoded
  by the developer)
- **Menstrual Calendar Data menu** — **view-only**, shows students'
  menstrual calendar entries (reflection & nutrition-tracker data stay
  hidden from admin)

### 4.3 Out of Scope (intentionally excluded)

- Editing materials via admin (remains hardcoded by the developer)
- Exporting/displaying reflection & nutrition-tracker data to admin
  (stays private to the student)
- Digital parental consent feature (handled physically outside the
  system)
- Retake/reset of pre-test & post-test by students or admin
- Mobile application

---

## 4. User Flow

### Student Flow

```
Student registers & logs in (email + password)
   ↓
Home
   ↓
MUST complete Pre-test (22 knowledge + 20 attitude questions) — final,
no editing
   ↓
Materials: 4 modules, student opens sub-sections one by one
   → Materials tab (content + embedded YouTube popup video) read first
   → Reflection tab unlocks afterward → filled in once → read-only
   → Nutrition module: weekly eating-habit tracker (one-time entry)
   ↓ (at any time, not tied to module order)
Menstrual Calendar: student can keep adding new entries (ongoing)
   ↓
After ALL 4 modules (all sub-sections) are marked complete
   → Pre-test/Post-test menu auto-switches to Post-test
   ↓
Student completes Post-test — final, no editing
   ↓
Profile accessible anytime to view personal data
```

### Admin Flow

```
Admin logs in (/admin, email + password)
   ↓
Dashboard: real-time statistics & charts
   ↓
Can access: Respondents, Test Results, Manage Questions (CRUD),
Manage Materials (view), Menstrual Calendar Data (view)
   ↓
Export CSV/Excel anytime directly from the dashboard
```

---

## 5. Database Structure

### `users` (students)

| Column            | Type            | Notes                  |
| ----------------- | --------------- | ---------------------- |
| id                | bigint, PK      |                        |
| nama              | varchar         | name                   |
| email             | varchar, unique |                        |
| password          | varchar         | hashed                 |
| usia              | tinyint         | age, 13–18             |
| jenis_kelamin     | enum('L','P')   | gender                 |
| sekolah           | varchar         | school, nullable       |
| kelas             | varchar         | class, nullable        |
| email_verified_at | timestamp       | nullable               |
| timestamps        |                 | created_at, updated_at |

### `admins`

| Column     | Type            | Notes  |
| ---------- | --------------- | ------ |
| id         | bigint, PK      |        |
| nama       | varchar         | name   |
| email      | varchar, unique |        |
| password   | varchar         | hashed |
| timestamps |                 |        |

### `modul` (modules)

| Column      | Type       | Notes                                                                  |
| ----------- | ---------- | ---------------------------------------------------------------------- |
| id          | bigint, PK |                                                                        |
| nama        | varchar    | name — Kejar Mimpi / Investasi Gizi / Berpikir Kritis / Kenali Tubuhmu |
| slug        | varchar    |                                                                        |
| urutan      | tinyint    | order, 1–4                                                             |
| cover_image | varchar    | nullable                                                               |
| timestamps  |            |                                                                        |

### `sub_bagian` (sub-sections)

| Column           | Type               | Notes                                   |
| ---------------- | ------------------ | --------------------------------------- |
| id               | bigint, PK         |                                         |
| modul_id         | bigint, FK → modul |                                         |
| judul            | varchar            | title                                                                                     |
| konten_view      | varchar            | dot-path to the Blade view holding this sub-section's content, e.g. `modul.kejar-mimpi.mimpi-itu-apa` — not raw HTML; the file lives under `resources/views/modul/` and is built from the reusable components in `resources/views/components/` (tip-box, highlight, checklist) |
| urutan           | tinyint            | order                                   |
| video_youtube_id | varchar            | nullable                                |
| timestamps       |                    |                                         |

> **Deviation note (decided Sesi 9):** originally spec'd as `konten longtext`
> storing raw HTML/Blade markup. Changed to `konten_view` (a view path
> reference) because content is hardcoded by the developer with no CMS/admin
> edit path — storing it as a DB string and re-interpreting it at runtime
> would mean reinventing what Blade's own view resolution already does.
> Actual content lives in ordinary `.blade.php` files, which get full,
> native support for the reusable components (`<x-tip-box>`, `<x-highlight>`,
> `<x-checklist>`).

### `progress_modul` (module progress)

| Column            | Type                    | Notes                             |
| ----------------- | ----------------------- | --------------------------------- |
| id                | bigint, PK              |                                   |
| user_id           | bigint, FK → users      |                                   |
| sub_bagian_id     | bigint, FK → sub_bagian |                                   |
| materi_selesai    | boolean                 | material completed, default false |
| materi_selesai_at | timestamp               | nullable                          |
| timestamps        |                         |                                   |

### `refleksi` (reflection)

| Column        | Type                    | Notes                             |
| ------------- | ----------------------- | --------------------------------- |
| id            | bigint, PK              |                                   |
| user_id       | bigint, FK → users      |                                   |
| sub_bagian_id | bigint, FK → sub_bagian |                                   |
| jawaban       | text                    | answer                            |
| is_locked     | boolean                 | default false → true after submit |
| submitted_at  | timestamp               | nullable                          |
| timestamps    |                         |                                   |

### `tracker_gizi` (nutrition tracker)

| Column       | Type               | Notes                             |
| ------------ | ------------------ | --------------------------------- |
| id           | bigint, PK         |                                   |
| user_id      | bigint, FK → users |                                   |
| data         | json               | weekly eating-habit summary       |
| is_locked    | boolean            | default false → true after submit |
| submitted_at | timestamp          | nullable                          |
| timestamps   |                    |                                   |

### `kalender_haid` (menstrual calendar — ongoing, one-to-many per student)

| Column          | Type               | Notes                      |
| --------------- | ------------------ | -------------------------- |
| id              | bigint, PK         |                            |
| user_id         | bigint, FK → users |                            |
| tanggal_mulai   | date               | start date                 |
| tanggal_selesai | date               | end date, nullable         |
| catatan         | text               | notes / symptoms, nullable |
| timestamps      |                    |                            |

### `kuesioner_soal` (questionnaire questions)

| Column         | Type                        | Notes                                               |
| -------------- | --------------------------- | --------------------------------------------------- |
| id             | bigint, PK                  |                                                     |
| tipe           | enum('pengetahuan','sikap') | knowledge / attitude                                |
| pertanyaan     | text                        | question                                            |
| jawaban_benar  | varchar                     | correct answer, nullable, knowledge type only (T/F) |
| reverse_scored | boolean                     | default false, attitude type only                   |
| urutan         | tinyint                     | order                                               |
| timestamps     |                             | managed via CRUD in admin's Manage Questions        |

### `hasil_kuesioner` (questionnaire results)

| Column               | Type                          | Notes                       |
| -------------------- | ----------------------------- | --------------------------- |
| id                   | bigint, PK                    |                             |
| user_id              | bigint, FK → users            |                             |
| tipe_sesi            | enum('pre','post')            | session type                |
| skor_pengetahuan     | decimal                       | knowledge score, percentage |
| kategori_pengetahuan | enum('Baik','Cukup','Kurang') | Good / Fair / Poor          |
| skor_sikap           | decimal                       | attitude score, total       |
| submitted_at         | timestamp                     |                             |
| timestamps           |                               |                             |

### `hasil_kuesioner_detail` (result details)

| Column             | Type                         | Notes            |
| ------------------ | ---------------------------- | ---------------- |
| id                 | bigint, PK                   |                  |
| hasil_kuesioner_id | bigint, FK → hasil_kuesioner |                  |
| soal_id            | bigint, FK → kuesioner_soal  |                  |
| jawaban_siswa      | varchar                      | student's answer |
| timestamps         |                              |                  |

---

## 6. Folder Structure

\`\`\`
app/
  Console/
    Commands/
      ExportHasilKuesioner.php      # optional CLI export, alongside dashboard export buttons
  Http/
    Controllers/
      Auth/                         # Breeze default (student)
      Admin/
        Auth/                       # admin guard auth
        DashboardController.php
        RespondenController.php
        HasilTestController.php
        SoalController.php          # CRUD kuesioner_soal
        MateriController.php        # view-only
        KalenderHaidController.php  # view-only
      ModulController.php
      SubBagianController.php
      KuesionerController.php       # pre-test / post-test logic
      RefleksiController.php
      TrackerController.php         # gizi mingguan + kalender haid (student side)
  Models/
    User.php
    Admin.php
    Modul.php
    SubBagian.php
    ProgressModul.php
    Refleksi.php
    TrackerGizi.php
    KalenderHaid.php
    KuesionerSoal.php
    HasilKuesioner.php
    HasilKuesionerDetail.php

database/
  migrations/
  seeders/
    ModulSeeder.php                 # seeds 4 modules + sub-sections from client docs
    KuesionerSoalSeeder.php         # seeds 22 knowledge + 20 attitude questions + answer key

resources/
  views/
    layouts/
    auth/                           # Breeze default (student)
    admin/
      auth/
      dashboard.blade.php
      responden/
      hasil-test/
      soal/
      materi/
      kalender-haid/
    modul/
      index.blade.php
      show.blade.php
      <modul-slug>/
        <sub-bagian-slug>.blade.php  # one file per sub-section (see §5 sub_bagian.konten_view deviation note); built from resources/views/components/ (tip-box, highlight, checklist)
    kuesioner/
      pretest.blade.php
      posttest.blade.php
    refleksi/
    tracker/
    components/

routes/
  web.php                           # student routes
  admin.php                         # admin routes (guard: admin)

\`\`\`

PRD_GenResilUp.md            # this file — full requirements + agent brief

# this file — full requirements + DB schema (read on-demand)
---

## 7. Non-Functional Requirements
Mobile-first for the student side; the admin side may bedesktop-oriented
Submission integrity: pre-test/post-test & reflection/nutritiontracker cannot be edited after submission — validated on the backend,not just a disabled button on the frontend
Privacy: reflection & nutrition-tracker data are never shown toadmin; parental consent is handled physically outside the system
Separate auth: student and admin guards cannot access eachother's pages
```
