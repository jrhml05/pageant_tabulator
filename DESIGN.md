# Design: Mr. & Ms. LCUAA 2026 Tabulation

Direction chosen by the owner: a clean operations console, light and dark themes, dressed in the LCUAA logo's crimson.

**Design read:** a scoring console for two audiences. The tabulator works at a laptop between segments; judges score on tablets during a live show, often in a dim venue, over a WLAN with no internet.

**Dials:** ENERGY 1 / RHYTHM 1 / MOTION 1. The screens are tools used under time pressure, so calm and predictable wins. Every results page has the same shape on purpose, so the tabulator never has to hunt for the print button.

## Decisions and reasons

| Decision | Reason |
|---|---|
| Warm neutral scale plus one crimson accent (`--accent`), taken from the LCUAA logo | The system wears the organizer's colors. Crimson marks the one primary action per screen (Lock in scores, Save finalists, Sign in) and where you are (current nav item and tab). Neutrals lean warm so the grays sit with the red instead of against it. Dark theme uses a lighter crimson (#f25c5c) so it keeps 4.5:1 on the dark surface. |
| Green (`--success-ink`) only for "all judges locked" | It's the one positive state the tabulator waits for, and it also carries a lock icon and text, not color alone. |
| Signal orange (`--danger`, #b84a00 light / #fb923c dark) only for errors, out-of-range scores, ties the board must settle, saved finalists that no longer match the Round 1 top 5, the Delete candidate button, and Clear everything on Reset scores | With crimson as the brand, errors can't be red too: an error would look like a primary button. Orange is a different hue from crimson, still reads as an alarm, and every error also carries an icon, a word or a tinted background, so it doesn't rely on hue alone. Ties show in orange only once every judge has locked in (or at the top 5 cut); while sheets are open they are routine and read as plain text. Stale finalists put the wrong candidates on the Round 2 sheet, so they count as an error; while Round 1 scoring is unfinished they read as plain text too. Deleting a candidate also deletes their scores, so it earns the same weight, and it sits alone at the bottom of the edit page. Clear everything wipes the whole night the same way and sits alone at the bottom of Reset scores; the per-round, segment and criterion resets above it are secondary buttons behind a confirm, so one orange button stays the loud one. The danger button is filled with the orange tint, so it never reads as a crimson primary button. |
| The LCUAA mark (`<x-event-logo>`) beside the event name in the admin sidebar and the judge header, above the sign-in form (the art panel is hidden on phones and tablets), at the top left of every printed sheet (the Mr. & Ms. LCUAA 2026 event logo takes the top right), and as the favicon | It is the source of the palette. It appears only next to the event name, never near scores. On the dark theme its dark red fades into the surface, so it sits on a small white tile there. |
| Sign-in art panel: the Mr. & Ms. LCUAA 2026 event logo (`public/assets/img/event-art.jpg`) on white | The event logo is drawn on white, with silver swooshes and gray lettering that would vanish on crimson, so the panel takes the logo's own white background. Without the file the panel falls back to the event name on the LCUAA logo's crimson fade (`.brand-panel`), the one gradient in the system. |
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
| Jump strip of pair numbers (check = scored, warning = error) | 9 pairs make a long page; the strip shows at a glance what is left and jumps there. |
| Casual wear (ranked): a 3×3 grid of rank buttons on each card, no drag and no typed ranks | Tapped on a tablet mid-show. Dragging misfires and reshuffles cards the judge didn't touch; typed ranks allow duplicates. The 3×3 grid of 48px buttons fits beside the photo, so a pair still sits on one screen. The selected rank takes the accent tint and border, like the view switch. |
| A rank someone holds shows their number under it; tapping it moves the rank and says so | The judge sees who has rank 3 before taking it. The rank moves instead of swapping so no card changes to a rank the judge didn't pick: the old holder is left without one, the strip shows the gap, and a notice names who needs a new rank. Tapping a candidate's own rank does nothing, so a double tap can't clear it. |
| Casual wear ranking strip (1st to 9th, per division) in place of the jump strip | It doubles as the check before lock-in: the judge reads their order top to bottom. Open places are dashed. |
| "Clear ranking" beside the view switch, away from Lock in; its confirm uses the orange danger button | Starting over is easier than moving nine ranks one by one, but it wipes the judge's work, so it sits apart from the primary action and asks first. It clears only the divisions on screen ("Clear Mr. ranking" in Mr. only) and disappears when nothing is ranked or the sheet is locked. |
| Lock in stays disabled until every score is in; "Next to score" sits beside it | The judge always sees the next step, instead of a failed lock and an error dialog. |
| Segment changes arrive as a banner, never an automatic page change on an open sheet | A judge mid-entry is not pulled away; the waiting screen, with nothing to lose, does jump by itself. |
| 48px buttons and 44px inputs on the judge app | Tapped on tablets during a live segment. |
| Motion: color transitions, drawer slide, chevron turn | Each shows a state change the user caused. Nothing loops; reduced motion turns them off. |
| Dark theme follows the device until toggled | A bright white tablet glares in a dim venue; the toggle lets each judge choose. |

## Identity motif

The candidate number: "No. 3" in semibold tabular figures heads every judge card and leads every report row.
