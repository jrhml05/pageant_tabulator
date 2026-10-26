# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A Laravel 13 + Livewire 4 (PHP 8.3+) tabulation system for the Mr. & Ms. LCUAA 2026 pageant (branch `lcuaa-2026-scoring`, built on `lcuaa-pageant`, which was adapted from the UEP 2024 build on `2024-UEP-Pageant`). Judges score candidates on tablets over the local network; the tabulator opens segments, watches judges lock in, and prints results.

## Commands

```bash
composer install
cp .env.example .env            # then set the DB (MySQL or SQLite both work)
php artisan key:generate
php artisan migrate:fresh --seed
npm install && npm run dev      # Vite 8 + Tailwind CSS 4; npm run build for production
php artisan event:serve         # for the event: resizes photos, 4 workers, 0.0.0.0:80 (--port to change)
php artisan candidates:photos   # web-sized photo copies in public/assets/img/{mr,ms}/web (gitignored)
php artisan scores:clear        # after a rehearsal: deletes scores, locks, open segments, finalist marks
vendor/bin/pint                 # formatter
php artisan test                # in-memory SQLite (phpunit.xml)
php artisan test --filter=TabulatorTest
```

Seeded accounts sign in with a username; each password equals the username: admins `admin1`, `admin2`; pre-pageant panel `prejudge1`–`prejudge3`; pageant night panel `judge1`–`judge5`. Candidates are seeded as Mr. and Ms. No. 1–9, one pair per school (`CandidateSeeder`).

Candidate photos live in `public/assets/img/mr/` and `public/assets/img/ms/`, named by candidate number (e.g. `1.jpg`).

## Scoring rules

All of it is in `config/pageant.php`: divisions, panels, rounds, the finalist count (5), placement titles, and each segment's criteria as `[label, max points]`. A segment's points add up to its weight, so each round totals 100.

- Round 1: Talent 15 and Thematic wear 15 (pre-pageant panel, 3 judges); Swim wear 15, Formal / evening wear 15, Beauty of face 20, Wit & verbal ability 20 (pageant night panel, 5 judges).
- Talent has a `weight` (15) in the config: judges score it out of 100 (Skills 50 + Performance 50), and `Segment::scale()` turns its average into its 15 in the Round 1 total. `Segment::weight()` is the segment's share of its round (its points when there is no `weight`); `maxPoints()` is what judges score out of.
- Round 2 (`final`): the saved finalists only, scored fresh by the pageant night panel: impression 50 + intelligence 50. Placed by average (see below).
- Casual wear (`casual_wear`): a separate award, not part of either round. Every candidate, ranked by each pageant night judge (below). A segment with no `round` in the config is an award (`Segment::isAward()`, `Segment::awards()`); its scores never reach `roundOne()` or the finalists.
- Casual wear is `ranked` in the config (`Segment::isRanked()`): no points or criteria. Each judge ranks each division 1 to its candidate count (`Segment::rankLimit()`), each rank once, saved as one score row per candidate with criterion `rank` (`Segment::RANK`) and the rank as `points`. The judges' ranks are added up (`rank_total`) and the lowest total wins; equal totals tie for the board, with no tie-break. Until every judge has ranked a candidate, `Tabulator::segment()` compares average ranks, so a missing judge doesn't lower a total.
- Every other segment and round places by average: the judges' totals for a candidate added up and divided by the number of judges, then ranked highest first (see below).

`App\Scoring\Tabulator` does the maths, in hundredths (ints) so sums and tie checks are exact:
- A judge's total counts only when every criterion has points. A segment average is the sum of the counted totals divided by how many there are, rounded to 2 decimals, and the segment ranks by it, highest first. While scoring is unfinished the average counts the judges who have scored that candidate (`counted`); results pages flag candidates not yet scored by every judge.
- Ranks are competition ranks (1, 2, 2, 4). Ties are flagged for the board; the only automatic tie-break is Round 1's (below).
- Each segment ranks on its own by its average, for that segment's winner.
- Round 1 is placed by weighted scores. Each segment's score is its average (rounded to 2 decimals) × `Segment::scale()`, in ten-thousandths so Talent's 15% stays exact (87.33 → 13.0995); the total adds the six, out of 100, and the highest wins. It counts whatever is scored so far; a row is `complete` once every judge of every segment has scored the candidate. Totals are compared before rounding and shown to 2 decimals, so two totals can both show 81.10 and still rank 1 and 2.
- A Round 1 tie on the total goes to the higher average in `config('pageant.tie_break')` (Beauty of face, then Q&A); the row's `tie_break` lists the segments it took. Still level after that: tied, for the board. `cutoff_tie` flags a tie left across the last finalist spot.
- Round 2 is the `final` segment's average.

## Architecture

- **Data:** `users` (`username`, `role` admin/judge, `panel` prepageant/pageant), `candidates` (`division` + `number`, unique together; `school`, shown only on printed results and the Candidates page, never to judges; `is_finalist`; `announce_order`), `open_segments` (a row means open), `scores` (one row per segment × candidate × judge × criterion, `points` decimal(5,2) nullable), `score_locks` (segment × judge; one lock covers both divisions). Score rows are created as judges type; nothing is pre-seeded.
- **`App\Scoring\Segment`** wraps a config entry: `open($panel)`, `judges()` (panel members by id; seat order is Judge 1, 2, ...), `candidates($division)` (finalists only after Round 1).
- **Judge sheet:** `App\Http\Livewire\Judge\ScoreSheet` serves every segment. Round 1 rows pair Ms. and Mr. candidates by number; Round 2 lines finalists up side by side. `viewMode` switches between that side-by-side view and one division (`showView()`); it is kept in the session so the next segment opens the same way. `points` is keyed `c{candidate id}` then criterion. A ranked segment has no inputs: each card (`livewire/judge/rank-card`) has a button per rank calling `rank($candidateId, $rank)`, which takes the rank from whoever held it in that division (`moved` says who needs a new one) and ignores a tap on the rank the candidate already has; `updatedPoints()` refuses typed ranks. A ranking strip of places 1st to 9th per division replaces the jump strip. "Clear ranking" (`clearRanking()`, behind a confirm) deletes this judge's ranks in the divisions on screen, both when side by side; it is refused once locked. The browser sends updates per field *or per whole candidate* (`points.c3` → array), so `updatedPoints()` diffs the whole sheet against saved rows instead of trusting the key. Invalid entries are saved as null. It refuses changes when the segment is closed, belongs to another panel, or is locked. `lock()` checks the saved rows, not only the screen (for a ranked segment: each division's ranks run 1 to its count, once each).
- **Judge flow:** `/judge-app` redirects to the first open segment the judge hasn't locked, or shows a waiting screen. `app.js` polls `/judge-app/status` every 4 s: the waiting screen jumps to the sheet; an open sheet shows a banner instead of navigating away.
- **Admin:** `/home` is scoring control (open/close segments, lock status, unlock a judge). Results are live, with no rank buttons: `/results/{division}/round-1` (with the finalists form) and `/results/{division}/{segment}` (`?judge=N` for a judge's sheet; Talent's adds what each total is worth at 15%, `Segment::weighted()`). Round 2's page adds placements. Awards get their own sidebar group, a "Pageant night, Special award" group on scoring control and a "Special awards" group on the reset page (segment and criterion resets only). Segment sheets list candidates by number. `/results/{division}/{segment}/winners` (`Tabulator::winners()`) is "Announcement of winners" on Round 2: the top 5 for the emcee, 4th runner-up first and the winner last, titled by `config('pageant.placements')`; tied candidates share a placement and are flagged for the board. On every other segment it is "Print winner": the winner only, with a tie for first flagged. The sidebar has one results list; each results page switches Ms./Mr. in place (`<x-results-header>`, keeping `?judge=`), and the sidebar opens the division last viewed (`session('results.division')`). Report pages poll themselves: `app.js` swaps each `[data-refresh-region]` whose markup changed.
- **Reset scores** (`/reset-scores`, `App\Scoring\ScoreReset`): deletes a criterion's, a segment's or a round's scores plus the segment's lock-ins, for clearing a dry run; "Clear everything" is `scores:clear`. Open state and finalists are left alone. Each reset changes a per-segment mark in the cache; a judge's open sheet compares it on every edit and reloads from the saved rows instead of saving its stale entries back.
- **Finalists:** saved on each Round 1 results page. A lock-in covers the finalists at the time: if saving changes the set and a finalist isn't fully scored in Round 2 (`Segment::unscored()`), the Round 2 locks are deleted so judges can score them (the form asks first once Round 2 has scores or locks). A dropped finalist's Round 2 scores are kept and count again if re-ticked. Scoring control, segment pages and the winners PDF don't call a fully locked segment final while someone is unscored. The finalists list refreshes with the scores; `app.js` keeps ticks changed since load (`data-keep-ticks`).
- **Top 5 announcement:** `App\Scoring\Announcement` gives each division's finalists a random call order when they are saved (kept if the same set is saved again) or on Reshuffle. A draw that matches number order, Round 1 rank order, or the previous order is redrawn (3+ finalists). `/announcement/print` prints both divisions with no ranks or scores.
- **Auth:** `LoginController::username()` is `username`. `user-access:<role>` middleware sends the wrong role to its own start page. Registration, password reset and email verification are off.

## UI

`DESIGN.md` records the design direction and the reason for each visual choice. Read it before changing the look.

Tailwind CSS 4 through `@tailwindcss/vite`; there is no `tailwind.config.js`. Design tokens and the few shared classes (`.btn`, `.card`, `.input`, `.data-table`, `.tie`, `.finalist`) are in `resources/css/app.css`. Dark mode is a `dark` class on `<html>`, set before paint by `partials/head.blade.php` and toggled by `resources/js/app.js`. IBM Plex Sans and Font Awesome are bundled by Vite, so the app works on a LAN with no internet.

- Admin layout: `layouts/master` with the sidebar in `layouts/navigation`. Judge layout: `judge_app/layouts/app`. Login: `layouts/app`.
- Results tables live in `admin/results/tables/*` and are shared by the page and its PDF through a `$print` flag, so they can't drift apart. PDFs extend `admin/results/pdf/layout.blade.php` (plain CSS, because dompdf can't render Tailwind). Every printed sheet has the LCUAA mark at the top left and the Mr. & Ms. LCUAA 2026 event logo at the top right (inlined as data URIs from `public/assets/img/lcuaa-logo.png` and `event-art-print.jpg`, so dompdf needs no file or network access) and ends with signature lines (`pdf/signatures`): its judges, the Tabulator, then `config('pageant.board')` (LCUAA National Board). The announcement sheet has the Tabulator and the board.

## Serving

`server.php` in the project root replaces Laravel's router for `php artisan serve`. PHP's built-in server sends public files without caching headers, so this router sends them itself with ETag/Last-Modified, 304s, and a max-age (a year for `/build/assets`, a day for `?v=` photo URLs, 5 minutes otherwise). `<x-candidate-photo>` uses the `web/` copy when it is newer than the original and adds `?v=<mtime>`.

## Gotchas

- Changing a segment key in `config/pageant.php` orphans its saved scores and open state, which reference the key as a string.
- Don't declare PHP functions inside Blade `@php` blocks: re-rendering a view in one process, as Livewire tests do, fatals on redeclare. Import classes with `@use`, not `use` inside `@php`.
- Livewire tests send per-field updates, the browser often sends per-candidate ones; cover both (see `ScoreSheetTest`).
