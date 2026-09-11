# GenResilUp++ — Consolidated PRD & Agent Operating Brief

**Document version:** 3.0 (merged — reconciles the earlier operating brief with the updated PRD v2.0)
**Status:** Requirements finalized, ready for development
**Platform:** Web application (Laravel, Blade, MySQL)

> **Merge note:** This document reconciles two prior files. Where the two
> conflicted, the **updated PRD (v2.0)** was treated as authoritative:
> the **admin dashboard is in scope**, and the **menstrual calendar is an
> ongoing/multi-entry feature**, not a one-time entry. Everything from
> the older operating brief that didn't conflict (folder structure, run
> instructions, agent rules, completion criteria) has been kept and
> updated to match.

"Split note: This file is loaded every session and holds only what isalways relevant: tech stack, build order, run instructions, agent rules,and completion criteria. Full requirement details — background, roles &access, scope, user flow, database schema, folder structure, andnon-functional requirements — live in PRD_GenResilUp.md; read iton-demand when a task needs them (e.g. before writing migrations orbuilding admin menus). "

Project in one line: Teen resilience education platform (4 modules:Kejar Mimpi, Investasi Gizi, Berpikir Kritis, Kenali Tubuhmu) for studentsaged 13–18, built for a lecturer's research — the pre-test gates moduleaccess, the post-test unlocks after all modules complete (both final, noretakes), and the admin (client/lecturer) has a separate statisticsdashboard with question CRUD and CSV/Excel export. All module content andthe questionnaire are hardcoded from client-supplied documents (no CMS).

" Precedence rule: PRD_GenResilUp.md is the source of truth for allbehavior and rules (gating, access, submission integrity). The design/mockups and DESIGN.md are the source of truth for visual style only(colors, typography, layout, components). If a mockup implies behaviorthat contradicts the PRD (e.g. the padlock icons on the home mockup area "not yet completed" status indicator, NOT sequential module locking —all 4 modules open after the pre-test per PRD §3.1), the PRD wins. "

---

## 1. Tech Stack

- **Framework:** Laravel
- **Rendering:** Blade (server-rendered, no separate API/SPA)
- **Database:** MySQL
- **Auth:** Laravel Breeze — **two separate guards/roles**:
    - Student: email + password
    - Admin (client): email + password, separate area (`/admin`)
- **Frontend styling:** Tailwind CSS (via Laravel's default Breeze
  scaffold)
- **Chart library:** Chart.js or ApexCharts (admin dashboard — donut &
  bar charts)
- **Export:** CSV/Excel export directly from the admin dashboard
  (`maatwebsite/excel` or native CSV), plus an optional Artisan console
  command for scripted/manual export
- **Video:** YouTube embed via popup player inside the Materials tab,
  not a page redirect

---
## 2. How to Run

```bash
composer install
cp .env.example .env
php artisan key:generate

# configure DB credentials in .env, then:
php artisan migrate --seed

npm install
npm run dev      # or: npm run build for production assets

php artisan serve
```

Seeders populate the 4 modules, their sub-sections, and the full 22 + 20
question bank with the official answer key — pulled from the
client-supplied `.docx`/`.pdf` files, not written from scratch.

---

## 3. Development Order (Build Order)

### Phase 1 — Foundation

1. Set up Laravel project + migrate all tables above
2. Student auth (Breeze) + separate admin auth (`admin` guard)

### Phase 2 — Questionnaire (core gating logic)

3. Questionnaire structure (`kuesioner_soal`) — seed 22 knowledge + 20
   attitude questions from the client's documents
4. Pre-test form + material-locking logic + final submission
5. Automatic scoring logic (knowledge category, total attitude score)

### Phase 3 — Module Content

6. Module & sub-section seeder from the client's `.docx`/PDF materials
7. Design reusable visual components (tip boxes, highlights,
   checklists, etc.) for the Materials tab — not plain text
8. Progress tracking per sub-section (basis for gating the Reflection
   tab & post-test)

### Phase 4 — Sub-section Features & Special Screens

9. Reflection tab (locked → submit once → read-only)
10. Weekly nutrition tracker (Nutrition module, one-time entry)
11. Menstrual Calendar screen (ongoing, CRUD entries by student)
12. Embedded YouTube video in the Materials tab (popup player)

### Phase 5 — Post-test

13. Auto-switch menu to Post-test after all modules are completed
14. Post-test form (reuse Phase 2 structure, session type = `post`)

### Phase 6 — Admin Dashboard

15. Admin layout (sidebar Data: Dashboard/Respondents/Test Results;
    Content: Manage Questions/Manage Materials) — following the
    SehatEdukasi reference
16. Dashboard: stat cards + charts (Chart.js/ApexCharts) + Recent
    Activity table
17. Manage Questions (full CRUD)
18. Manage Materials (view-only) & Menstrual Calendar Data (view-only)
19. Export CSV/Excel from the dashboard

### Phase 7 — Polish & QA

20. Full responsive/mobile-first styling (student side)
21. End-to-end testing: student (register → pretest → 4 modules →
    posttest) and admin (login → dashboard → manage questions → export)
22. Revisions based on preview to the client

---

## 4. Agent Rules

These rules apply to any AI coding agent (or human contributor) working
in this repo:

1. **Never delete a file without asking first.** Always confirm with
   the developer before running a delete, even for something that looks
   obviously unused or generated.
2. **Never refactor code that wasn't asked for.** Stick to the scope of
   the current task. If you notice something worth refactoring, mention
   it — don't just do it.
3. **Only output changed or newly-added code**, not full unchanged
   files, when reporting back on a change.
4. **Break feature work into individual, clearly labeled steps** — one
   step per message/commit, not one large dump of the whole feature at
   once.
5. **Match the actual project files exactly.** Don't invent file names,
   paths, or structure that don't correspond to what's really in the
   repo — check before assuming.
6. **Deliver code changes as downloadable files**, not as inline code
   blocks pasted into chat, whenever the workflow is a step-by-step
   feature update.
7. **Follow the phased build order in §9** unless told otherwise —
   don't jump ahead to later-phase features (e.g. don't build the admin
   dashboard/export tooling before the questionnaire gating logic and
   module content exist).
8. **Pre-test/post-test submissions are final** — any code path that
   touches questionnaire answers must enforce "no edit after submit" at
   the backend/validation level, not just hide the UI.
9. **The admin dashboard is in scope** (§4.2) — build it in Phase 6, per
   §9. Admin can only view materials and menstrual-calendar data
   (view-only), and never sees reflection or nutrition-tracker data —
   enforce this at the query/controller level, not just by omitting it
   from the view.

---

## 5. Completion Criteria (Definition of Done)

A feature or the project as a whole is considered done when:

- [ ] Student can register/login with email + password
- [ ] Materi is inaccessible until the pre-test is submitted
- [ ] Pre-test correctly captures 22 knowledge (B/S) + 20 attitude
      (SS/S/TS/STS) answers and cannot be edited after submit
- [ ] All 4 modules and their sub-sections render with the intended
      styled layout (not raw document/PDF viewing)
- [ ] Each sub-section's reflection sheet saves a one-time free-text
      response per student
- [ ] The weekly nutrition tracker saves a one-time entry per student
- [ ] The menstrual calendar lets a student add multiple, ongoing
      entries (not limited to one), with full history retained
- [ ] Videos play in an in-page popup, not a redirect to YouTube
- [ ] Post-test only becomes accessible after all module sub-sections
      are marked complete, and is final on submit
- [ ] Knowledge answers auto-score into Baik/Cukup/Kurang categories;
      attitude answers auto-score into the total per the official
      scoring guide
- [ ] Admin can log in separately from students (`/admin`) and cannot
      access student-only pages, and vice versa
- [ ] Admin dashboard shows correct stat cards, donut/bar charts, and a
      Recent Activity table
- [ ] Admin can fully CRUD questionnaire questions (Manage Questions)
- [ ] Admin can view (but not edit) Materials and Menstrual Calendar
      data; reflection and nutrition-tracker data are never exposed to
      admin, anywhere
- [ ] Export (CSV/Excel) from the admin dashboard produces pre vs. post
      scored results, excluding reflection/tracker data by design
- [ ] Full flow (register → pretest → all modules → posttest → admin
      dashboard → export) has been manually tested end to end
- [ ] Mobile-first responsive check passed on the student-facing pages

--- 