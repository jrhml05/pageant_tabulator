# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A Laravel 13 + Livewire 4 (PHP 8.3+) tabulation system for the Mr. & Ms. LCUAA 2026 pageant (branch `lcuaa-pageant`, adapted from the UEP 2024 build on branch `2024-UEP-Pageant`). Judges score candidates on tablets over the local network; the admin views per-judge and combined results, computes rankings, and exports PDFs.

## Commands

```bash
composer install
cp .env.example .env            # then set DB_DATABASE (README uses mr_and_ms_lcuaa; MySQL only)
php artisan key:generate
php artisan migrate --seed      # or migrate:fresh --seed to reset
npm install && npm run dev      # Vite 8 + Tailwind CSS 4; npm run build for production
php artisan event:serve         # for the event: resizes photos, 4 workers, 0.0.0.0:80 (--port to change)
php artisan candidates:photos   # web-sized photo copies in public/assets/img/{mr,ms}/web (gitignored)
php artisan db:seed --class=PrelimSeeder        # reset/seed preliminaries score tables only
vendor/bin/pint                 # formatter
php artisan test                # only the stock Example tests exist
php artisan test --filter=ExampleTest
```

Seeders use `SET FOREIGN_KEY_CHECKS` and truncate, so they need MySQL; the phpunit sqlite settings are commented out.

Seeded logins (see README): admin `admin@mail.com` / `mrmslcuaa`; judges `judge1@mail.com`…`judge5@mail.com` / `mrmslcuaajudge<N>`.

Candidate photos live in `public/assets/img/mr/` and `public/assets/img/ms/`, named by candidate number (e.g. `1.jpg`).

## Architecture

**Everything is duplicated per division (Mr / Ms) and per category.** There's no generic category/criteria model. Each category has its own table, model, Livewire component, judge view, report view, and routes, in `Mr_*` and `Ms_*` pairs. A change to one category usually has to be repeated in its sibling (and often across all categories).

**Stages and categories** (see `StageSeeder`; the `stages.is_active` flag controls which stage judges see on `/judge-app`):
- Pre-Pageant: talent, rave wear → aggregate `*_prepageant_scores`
- Preliminaries: national costume, departmental uniform, swim wear, formal wear, Q&A → aggregate `*_prelim_scores`
- Final: `*_final_scores` (wit, projection, stage presence, overall impact), only for candidates with `is_active = 1`

**Scoring flow:** Score rows are pre-created with null criteria by seeders (one row per candidate × judge). Judges then fill them in. Each judge Livewire component (`app/Http/Livewire/Judge/{Prepageant,Preliminaries,Final}/{Mr,Ms}/*ScoreBoardComponent.php`) does the following:
- In `updatedRecords($value, $key)`, it saves the raw criteria of the edited candidate only (`$key` is `index.field`) on every change. It also writes a weighted value into the stage's aggregate table: `(criteria total / 100) * weight`, e.g. talent ×50 and each prelim category ×20.
- In `confirmedLockInScores()`, it validates per-criterion maximums (hard-coded in the component) and sets `is_lock = 1`.
- It uses SweetAlert through `$this->dispatch('swal:*', type: ..., message: ..., text: ...)`. Those named args become `event.detail` in the `window.addEventListener` handlers in `judge_app/layouts/app.blade.php`. The confirm dialogs call back with `Livewire.dispatch('confirmedLockInScores')`, which is handled by `$listeners`.

**Livewire 4 compatibility settings** (`config/livewire.php`):
- `class_namespace` is `App\Http\Livewire`, the Livewire 2 location.
- `legacy_model_binding` is `true`. The scoreboards bind inputs straight to Eloquent collections (`wire:model.live="records.{{ $index }}.mastery"`), which needs this flag plus a `$rules` entry for every bound field.
- Score inputs use `wire:model.live`. Plain `wire:model` no longer saves until a request is sent, so `updatedRecords()` would stop saving as the judge types.

**Rankings** are computed on demand, not live. The admin clicks buttons on the report pages, and those buttons make AJAX GET calls to the `*_rank` routes in the `Mr/MsLcuaa{PrePageant,Prelim,Final}ReportsController`. Each call does two things:
1. It ranks candidates per judge by aggregate score and writes the result to a column of `*_rankings`. Ties share a rank.
2. It sums the per-judge ranks into `*_final_ranks`, where the lowest sum wins.
`mr/ms_to_top_5_rank` flips `*_candidates.is_active` for the finalists. `mr/ms_final_score_seeder` routes run `Mr/MsFinalScoreSeeder` from the browser to create final score rows for those finalists.

**Judges are hard-coded as user IDs 2–4** (`for ($x = 2; $x <= 4; $x++)`, `whereIn('id', range(2, 4))` in seeders), and reports have fixed `judge1/2/3` views and routes. Adding or reordering judges requires changing these loops, seeders, and views.

**Auth:** `users.role` is `admin` or `judge`. Access is enforced by the `user-access:<role>` middleware (`app/Http/Middleware/UserAccess.php`). All admin routes and all judge routes (`/judge-app/...`) are two groups in `routes/web.php`. spatie/laravel-permission is installed but not used for access checks.

**PDFs** use barryvdh/laravel-dompdf: `PDF::loadView('admin.reports....pdf*')->setPaper([0,0,612,936], 'landscape')->stream()`. Each report has an HTML view plus `pdf*` views, per judge and combined.

## UI

`DESIGN.md` records the design direction and the reason for each visual choice (palette, type, radii, shadow, motion). Read it before changing the look.

Tailwind CSS 4 through `@tailwindcss/vite`; there is no `tailwind.config.js`. Design tokens (colors as CSS variables for light and dark) and the few shared classes (`.btn`, `.card`, `.input`, `.data-table`) are in `resources/css/app.css`. Dark mode is a `dark` class on `<html>`, set before paint by `partials/head.blade.php` and toggled by `resources/js/app.js`. The font (IBM Plex Sans) and Font Awesome are npm packages bundled by Vite, so the app works on a LAN with no internet.

- Admin layout: `layouts/master` with the sidebar in `layouts/navigation`. Judge layout: `judge_app/layouts/app`. Login: `layouts/app`.
- Report pages use `<x-report-header>`: it builds the Overall/Judge 1–3 tabs from the `{route}_judge{n}` naming and the rank/print buttons. Buttons with `data-action-url` (rank, mark top 5, create final sheets) are handled in `app.js`, which does a GET and then refreshes the tables, or shows an inline error. Report pages also poll themselves every 5 seconds while the tab is visible: `app.js` re-fetches the page and swaps each `<x-table-card>` (`data-refresh-region`) whose markup changed. If the number of tables changes, it does a full reload instead.
- Judge scoring views use `<x-judge.candidate-card>`, `<x-judge.score-field>` (its `max` must match the component's lock-in validation), `<x-judge.totals>`, and `<x-judge.action-bar>`.
- PDF views (`pdf*.blade.php`) extend `admin/reports/pdf-layout.blade.php` (plain CSS, because dompdf can't render Tailwind). Each PDF's table is a copy of the table in the on-screen report beside it, so change both together. Controller `*_pdf*` methods pass the same `$data` as their on-screen twin.

## Serving

`server.php` in the project root replaces Laravel's router for `php artisan serve`. PHP's built-in server sends public files without caching headers, so this router sends them itself with ETag/Last-Modified, 304s, and a max-age (a year for `/build/assets`, a day for `?v=` photo URLs, 5 minutes otherwise). `<x-candidate-photo>` uses the `web/` copy when it is newer than the original and adds `?v=<mtime>`.

## Gotchas

- Controllers reference views in lowercase (`admin.reports.prelim.mr...`), but the directories are capitalized (`resources/views/admin/reports/Prelim/Mr`). This only works on case-insensitive filesystems like macOS, so it breaks on Linux.
- Registration, password reset, and email verification are switched off in `Auth::routes()`. New users default to the `admin` role, so open registration would let anyone on the LAN create an admin.
- `cal_percentage()` is a global helper in `app/helpers.php` (Composer `files` autoload). Don't declare PHP functions inside Blade `@php` blocks: re-rendering the view in one process, as Livewire tests do, fatals on redeclare.
- `DatabaseSeeder` seeds only users, stages, candidates, rankings, and pre-pageant scores. Prelim and final score rows come from `PrelimSeeder` and the final-score-seeder routes.
