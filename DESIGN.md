# Design: Mr. & Ms. LCUAA 2026 Tabulation

Direction chosen by the owner: a clean operations console, light and dark themes.

**Design read:** a scoring console for two audiences. The tabulator works at a laptop between segments; judges score on tablets during a live show, often in a dim venue, over a WLAN with no internet.

**Dials:** ENERGY 1 / RHYTHM 1 / MOTION 1. The screens are tools used under time pressure, so calm and predictable wins. Every results page has the same shape on purpose, so the tabulator never has to hunt for the print button.

## Decisions and reasons

| Decision | Reason |
|---|---|
| Neutral gray scale plus one teal accent (`--accent`) | Teal marks the one primary action per screen (Lock in scores, Save finalists, Sign in) and where you are (current nav item and tab). It stays clear of the red that flags an out-of-range score. |
| Green (`--success-ink`) only for "all judges locked" | It's the one positive state the tabulator waits for, and it also carries a lock icon and text, not color alone. |
| Red (`--danger`) only for errors, out-of-range scores, ties the board must settle, and the Delete candidate button | It needs to be the loudest thing on screen when it appears, so nothing else uses it. Ties show in red only once every judge has locked in (or at the top 5 cut); while sheets are open they are routine and read as plain text. Deleting a candidate also deletes their scores, so it earns the same weight, and it sits alone at the bottom of the edit page. |
| IBM Plex Sans, bundled with Vite | Clear, tabular figures so score columns line up digit for digit, and 1/l/I are easy to tell apart. Bundled because the venue has no internet. |
| Font Awesome solid icons, bundled | Filled glyphs stay legible at 14px on tablets. Icons appear only beside a text label (print, rank, lock) or as the theme and menu toggles, which have accessible names. |
| Radii: 6px controls, 8px cards, tab groups and the lock-in dialog | Controls and containers read as different kinds of things. |
| No shadows; the lock-in dialog sits on a dimmed backdrop | Everything is flat and separated by 1px borders; the backdrop alone says the dialog is on top. |
| Borders at 3:1 (`--line-strong`) for inputs, secondary buttons, and the finalist and panel choices | Control edges need to be visible (WCAG 1.4.11). Dividers (`--line`) are lighter because they aren't controls. |
| Tables: sticky first column, centered numbers, final rank column tinted | On a phone the scores scroll sideways under the candidate number. The final rank is the number the tabulator reads out. |
| Judge cards: photo beside the inputs, number in the card header, never over the photo | The candidate posters carry their own number and name art. Beside, not above, keeps a Ms./Mr. pair on one tablet screen. |
| Judge sheet: Ms. and Mr. of the same number side by side, one row per pair | Candidates walk out in pairs, so the judge scores the pair on stage without scrolling between two lists. |
| Admin results: one sidebar list; Ms./Mr. switch on the page, beside Print | Each results page has the same shape for both divisions, so comparing them is one tap on the same screen instead of a second, near-identical menu. The switch keeps the judge tab. |
| View switch: Side by side, Ms. only, Mr. only; remembered for the judge's session | Some judges score one division at a time (or one division walks alone). The choice sticks across segments so they set it once. Lock in still needs both divisions, so a one-division view offers "Go to Mr. candidates (3 left)" when its own cards are done. |
| Jump strip of pair numbers (check = scored, warning = error) | 12 pairs make a long page; the strip shows at a glance what is left and jumps there. |
| Lock in stays disabled until every score is in; "Next to score" sits beside it | The judge always sees the next step, instead of a failed lock and an error dialog. |
| Segment changes arrive as a banner, never an automatic page change on an open sheet | A judge mid-entry is not pulled away; the waiting screen, with nothing to lose, does jump by itself. |
| 48px buttons and 44px inputs on the judge app | Tapped on tablets during a live segment. |
| Motion: color transitions, drawer slide, chevron turn | Each shows a state change the user caused. Nothing loops; reduced motion turns them off. |
| Dark theme follows the device until toggled | A bright white tablet glares in a dim venue; the toggle lets each judge choose. |

## Identity motif

The candidate number: "No. 3" in semibold tabular figures heads every judge card and leads every report row.
