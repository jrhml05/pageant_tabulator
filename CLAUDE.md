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

Seeded accounts sign in with a username; each password equals the username: admins `admin1`, `admin2`; pre-pageant panel `prejudge1`–`prejudge3`; pageant night panel `judge1`–`judge5`.

Candidate photos live in `public/assets/img/mr/` and `public/assets/img/ms/`, named by candidate number (e.g. `1.jpg`).

## Scoring rules

All of it is in `config/pageant.php`: divisions, panels, rounds, the finalist count (5), placement titles, and each segment's criteria as `[label, max points]`. A segment's points add up to its weight, so each round totals 100.

- Round 1: Talent 15 and Thematic wear 15 (pre-pageant panel, 3 judges); Swim wear 15, Formal / evening wear 15, Beauty of face 20, Wit & verbal ability 20 (pageant night panel, 5 judges).
- Round 2 (`final`, `'ranking' => 'rank_sum'`): the saved finalists only, scored fresh by the pageant night panel: impression 50 + intelligence 50. Placed by rank sum, not average (see below).

`App\Scoring\Tabulator` does the maths, in hundredths (ints) so sums and tie checks are exact:
- A judge's total counts only when every criterion has points. A segment average is the mean of the counted totals, rounded to 2 decimals.
- The Round 1 total is the sum of the rounded segment averages, so printed sheets add up.
- Ranks are competition ranks (1, 2, 2, 4) on those rounded figures. Ties are flagged, never broken automatically; the board decides. `cutoff_tie` flags a tie across the last finalist spot.
- Rank-sum segments (Round 2): each judge ranks the finalists by total (equal totals share a rank), the ranks are added, and the lowest sum places first; equal sums share a placement and are flagged. Only judges who have scored every finalist count (`rank_judges`), so every sum covers the same judges.

## Architecture

- **Data:** `users` (`username`, `role` admin/judge, `panel` prepageant/pageant), `candidates` (`division` + `number`, unique together; `is_finalist`; `announce_order`), `open_segments` (a row means open), `scores` (one row per segment × candidate × judge × criterion, `points` decimal(5,2) nullable), `score_locks` (segment × judge; one lock covers both divisions). Score rows are created as judges type; nothing is pre-seeded.
- **`App\Scoring\Segment`** wraps a config entry: `open($panel)`, `judges()` (panel members by id; seat order is Judge 1, 2, ...), `candidates($division)` (finalists only after Round 1).
- **Judge sheet:** `App\Http\Livewire\Judge\ScoreSheet` serves every segment. Round 1 rows pair Ms. and Mr. candidates by number; Round 2 lines finalists up side by side. `viewMode` switches between that side-by-side view and one division (`showView()`); it is kept in the session so the next segment opens the same way. `points` is keyed `c{candidate id}` then criterion. The browser sends updates per field *or per whole candidate* (`points.c3` → array), so `updatedPoints()` diffs the whole sheet against saved rows instead of trusting the key. Invalid entries are saved as null. It refuses changes when the segment is closed, belongs to another panel, or is locked. `lock()` checks the saved rows, not only the screen.
- **Judge flow:** `/judge-app` redirects to the first open segment the judge hasn't locked, or shows a waiting screen. `app.js` polls `/judge-app/status` every 4 s: the waiting screen jumps to the sheet; an open sheet shows a banner instead of navigating away.
- **Admin:** `/home` is scoring control (open/close segments, lock status, unlock a judge). Results are live, with no rank buttons: `/results/{division}/round-1` (with the finalists form) and `/results/{division}/{segment}` (`?judge=N` for a judge's sheet). Round 2's page adds placements. The sidebar has one results list; each results page switches Ms./Mr. in place (`<x-results-header>`, keeping `?judge=`), and the sidebar opens the division last viewed (`session('results.division')`). Report pages poll themselves: `app.js` swaps each `[data-refresh-region]` whose markup changed.
- **Top 5 announcement:** `App\Scoring\Announcement` gives each division's finalists a random call order when they are saved (kept if the same set is saved again) or on Reshuffle. A draw that matches number order, Round 1 rank order, or the previous order is redrawn (3+ finalists). `/announcement/print` prints both divisions with no ranks or scores.
- **Auth:** `LoginController::username()` is `username`. `user-access:<role>` middleware sends the wrong role to its own start page. Registration, password reset and email verification are off.

## UI

`DESIGN.md` records the design direction and the reason for each visual choice. Read it before changing the look.

Tailwind CSS 4 through `@tailwindcss/vite`; there is no `tailwind.config.js`. Design tokens and the few shared classes (`.btn`, `.card`, `.input`, `.data-table`, `.tie`, `.finalist`) are in `resources/css/app.css`. Dark mode is a `dark` class on `<html>`, set before paint by `partials/head.blade.php` and toggled by `resources/js/app.js`. IBM Plex Sans and Font Awesome are bundled by Vite, so the app works on a LAN with no internet.

- Admin layout: `layouts/master` with the sidebar in `layouts/navigation`. Judge layout: `judge_app/layouts/app`. Login: `layouts/app`.
- Results tables live in `admin/results/tables/*` and are shared by the page and its PDF through a `$print` flag, so they can't drift apart. PDFs extend `admin/results/pdf/layout.blade.php` (plain CSS, because dompdf can't render Tailwind).

## Serving

`server.php` in the project root replaces Laravel's router for `php artisan serve`. PHP's built-in server sends public files without caching headers, so this router sends them itself with ETag/Last-Modified, 304s, and a max-age (a year for `/build/assets`, a day for `?v=` photo URLs, 5 minutes otherwise). `<x-candidate-photo>` uses the `web/` copy when it is newer than the original and adds `?v=<mtime>`.

## Gotchas

- Changing a segment key in `config/pageant.php` orphans its saved scores and open state, which reference the key as a string.
- Don't declare PHP functions inside Blade `@php` blocks: re-rendering a view in one process, as Livewire tests do, fatals on redeclare. Import classes with `@use`, not `use` inside `@php`.
- Livewire tests send per-field updates, the browser often sends per-candidate ones; cover both (see `ScoreSheetTest`).
