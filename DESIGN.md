# Design: Mr. & Ms. LCUAA 2026 Tabulation

Direction chosen by the owner: a clean operations console, light and dark themes.

**Design read:** a scoring console for two audiences. The tabulator works at a laptop between segments; judges score on tablets during a live show, often in a dim venue, over a WLAN with no internet.

**Dials:** ENERGY 1 / RHYTHM 1 / MOTION 1. The screens are tools used under time pressure, so calm and predictable wins. Every report page has the same shape on purpose, so the tabulator never has to hunt for the rank button.

## Decisions and reasons

| Decision | Reason |
|---|---|
| Neutral gray scale plus one teal accent (`--accent`) | Teal marks the one primary action per screen (Rank candidates, Lock in scores, Sign in) and where you are (current nav item and tab). It stays clear of the red that flags an out-of-range score. |
| Green (`--success-ink`) only for "all judges locked" | It's the one positive state the tabulator waits for, and it also carries a lock icon and text, not color alone. |
| Red (`--danger`) only for errors and out-of-range scores | It needs to be the loudest thing on screen when it appears, so nothing else uses it. |
| IBM Plex Sans, bundled with Vite | Clear, tabular figures so score columns line up digit for digit, and 1/l/I are easy to tell apart. Bundled because the venue has no internet. |
| Font Awesome solid icons, bundled | Filled glyphs stay legible at 14px on tablets. Icons appear only beside a text label (print, rank, lock) or as the theme and menu toggles, which have accessible names. |
| Radii: 6px controls, 8px cards and tab groups, full round only on the switch | Controls and containers read as different kinds of things. |
| Shadow only on the selected segment (division switch) and the switch knob | Those two sit on top of a track; everything else is flat and separated by 1px borders. |
| Borders at 3:1 (`--line-strong`) for inputs, secondary buttons and the switch track | Control edges need to be visible (WCAG 1.4.11). Dividers (`--line`) are lighter because they aren't controls. |
| Tables: sticky first column, centered numbers, final rank column tinted | On a phone the scores scroll sideways under the candidate number. The final rank is the number the tabulator reads out. |
| Judge cards: number strip above the photo, never over it | The candidate posters carry their own number and name art. |
| 48px buttons and 44px inputs on the judge app | Tapped on tablets during a live segment. |
| Motion: color transitions, drawer slide, chevron turn | Each shows a state change the user caused. Nothing loops; reduced motion turns them off. |
| Dark theme follows the device until toggled | A bright white tablet glares in a dim venue; the toggle lets each judge choose. |

## Identity motif

The candidate number: "No. 3" in semibold tabular figures heads every judge card and leads every report row.
