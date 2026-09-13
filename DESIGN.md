# DESIGN.md — GenResilUp++ Visual Tokens

Sampled from the client mockups in `design/student/**` (dominant-color
histogram, so the hex values are the real ones, not estimates). Scope:
student-facing screens. The admin area has no mockup (`design/admin/` is
empty) and keeps its current SehatEdukasi-style look.

Behavior always follows `PRD_GenResilUp.md`; this file only governs looks.
Notably: the padlocks in `design/student/materi/materi-1.png` and the caption
"Selesaikan modul sebelumnya untuk membuka modul ini" are rendered as a
**"belum selesai" status marker**, not sequential locking — all 4 modules stay
open after the pre-test (PRD §3.1).

## Colors (`tailwind.config.js` → `theme.extend.colors.brand`)

| Token               | Hex       | Used for                                            |
| ------------------- | --------- | --------------------------------------------------- |
| `brand-forest`      | `#1F6B53` | primary: hero cards, buttons, active nav, headings   |
| `brand-forest-deep` | `#17553F` | pressed/hover state of primary                       |
| `brand-forest-soft` | `#4A8773` | muted text/dividers on a forest background           |
| `brand-cream`       | `#F9F6EB` | page background                                      |
| `brand-paper`       | `#FEFDFA` | card surface on cream                                |
| `brand-mint-soft`   | `#CFEDE0` | active nav pill, soft accent card, progress fill     |
| `brand-pink`        | `#FFE1E3` | screen header band (Tes, Kenali Tubuhmu)             |
| `brand-pink-soft`   | `#FFE9E6` | pre-test call-to-action card (dashed border)         |
| `brand-lilac`       | `#ECE8FE` | callout box ("Ingat ya", motivational note)          |
| `brand-amber`       | `#EEB64F` | accent: crown, squiggle underline, avatar            |
| `brand-amber-soft`  | `#FEEDC9` | secondary accent card                                |
| `brand-ink`         | `#232E2B` | body text and headings on light backgrounds          |
| `brand-line`        | `#E3E8DC` | hairline borders, dividers, progress track           |

The pre-restyle tokens (`brand-mint` `#7FC79A`, `brand-peach`) are gone — the
Materi content components were migrated to the table above.

Per-module accent, used for the header band and the module icon
(`Modul::getWarnaAttribute()` returns the key, the Blade file holds the class
so Tailwind can scan it): Kejar Mimpi → mint, Investasi Gizi → amber,
Berpikir Kritis → lilac, Kenali Tubuhmu → pink.

## Layout

- **Mobile-first, single column.** Content lives in `max-w-md mx-auto px-5`;
  the mockups are ~470px wide and the design does not widen on desktop.
- **Bottom tab bar**, 5 items, fixed: Home · Materi · Tes · Haid · Profil
  (`resources/views/components/bottom-nav.blade.php`). Active item = mint pill
  behind the icon + forest label. Page wrapper carries `pb-24` to clear it.
- Screens that have a colored hero band (Tes, module detail) run it full-bleed
  behind the heading; Home and Profil use a soft blob instead.

- Signed-out screens (login, register, password reset, admin login) use
  `layouts/guest.blade.php`: same cream page, one `max-w-md` column, form in a
  `rounded-3xl` paper card. No bottom nav.

## Shared form components

The Breeze components carry the palette so every remaining form follows without
being rewritten: `primary-button` (forest pill), `secondary-button` (outlined
pill), `danger-button` (rose pill), `text-input` and `input-label` (cream field,
`rounded-2xl`, forest focus ring), `auth-session-status` (mint note).

Choice inputs are rendered as pills, never as bare radios/checkboxes: the real
`<input>` stays in the DOM with `class="peer sr-only"` (focusable and readable
by screen readers, unlike `hidden`) and the visible `<span>` reacts via
`peer-checked:` / `peer-focus-visible:`. Used by the questionnaire and the
nutrition tracker.

## Shape & elevation

- Screens under a module (detail, sub-bagian, refleksi) open with a colored
  band in that module's accent (`modul/_band.blade.php`): back link, title,
  optional tagline and progress meta, `rounded-b-3xl`.
- Big surfaces (hero card, section card): `rounded-3xl`.
- List rows and small cards: `rounded-2xl`.
- Flat design — `shadow-sm` at most, never a heavy drop shadow.
- "Not yet available" state: muted, dashed border, padlock icon, reduced
  opacity — still clickable where the PRD says the content is open.

## Typography

Figtree (already loaded in `layouts/app.blade.php`). Page title
`text-3xl font-bold`, section heading `text-xl font-bold`, card title
`font-bold`, supporting copy `text-brand-ink/60`.

## Icons

Inline SVG, lucide-style, `stroke-width: 1.8`, `currentColor` — no icon
library. Emoji are used only inside module content (🎯 tujuan, 💡 tips,
✅/⚠️ checklist), matching the client's source `.md` documents.

## Illustrations

`public/images/chara-1.png` (girl writing) and `chara-2.png` (three students),
copied from `design/character/`. `chara-2` sits on Home under the greeting.
