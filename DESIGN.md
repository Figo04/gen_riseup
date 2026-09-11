# DESIGN.md — GenResilUp++ Visual Tokens

Sampled visually from `design/student/home/*.png` and `design/student/materi/*.png`.
Approximate hex values (no pixel-exact source file) — refine if the client
provides exact swatches. Scope: only what's needed for the Materials-tab
content components (Sesi 9). The rest of the student UI (dashboard, pretest)
still uses default Breeze/indigo styling and is untouched — restyling those
is a separate task, not part of this doc.

## Colors (`tailwind.config.js` → `theme.extend.colors.brand`)

| Token               | Hex       | Used for                                    |
| ------------------- | --------- | -------------------------------------------- |
| `brand-cream`       | `#F7F2E6` | page background                              |
| `brand-mint`        | `#7FC79A` | positive/active accent, borders               |
| `brand-mint-light`  | `#D8EFE0` | positive callout background (tip, ok state)   |
| `brand-peach`       | `#E8A87C` | caution accent, borders                       |
| `brand-peach-light` | `#F7DFC9` | caution callout background                    |
| `brand-ink`         | `#1F2A24` | headings / primary text on light bg           |

## Shape & elevation

- Cards / callout boxes: `rounded-2xl`, thin border in the accent color at
  low opacity (`border border-brand-mint/30`), no heavy shadow (mockups use
  flat cards, not drop shadows).
- Locked/disabled state (not used by these components, noted for later
  sessions): muted gray bg, dashed border.

## Icon convention

Emoji-as-icon, matching the client's source `.md` documents directly
(🎯 tujuan, 🌟 refleksi, 💡 tips, 🤝 bantuan, ✅/⚠️ checklist) — no icon
library needed.
