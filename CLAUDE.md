# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A Laravel 13 + Livewire 4 (PHP 8.3+) tabulation system for the Mr. & Ms. UEP pageant (branch `2024-UEP-Pageant`). Judges score candidates on tablets over the local network; the admin views per-judge and combined results, computes rankings, and exports PDFs.

## Commands

```bash
composer install
cp .env.example .env            # then set DB_DATABASE (README uses mr_and_ms_uep; MySQL only)
php artisan key:generate
php artisan migrate --seed      # or migrate:fresh --seed to reset
npm install && npm run dev      # Vite (Bootstrap 5 + Sass); npm run build for production
php artisan serve --host <local-ip> --port 80   # so tablets on the same LAN can reach it
php artisan db:seed --class=PrelimSeeder        # reset/seed preliminaries score tables only
vendor/bin/pint                 # formatter
php artisan test                # only the stock Example tests exist
php artisan test --filter=ExampleTest
```

Seeders use `SET FOREIGN_KEY_CHECKS` and truncate, so they need MySQL; the phpunit sqlite settings are commented out.

Seeded logins (see README): admin `admin@mail.com` / `mrmsuep`; judges `judge1@mail.com`…`judge5@mail.com` / `mrmsuepjudge<N>`.

Candidate photos live in `public/assets/img/mr/` and `public/assets/img/ms/`, named by candidate number (e.g. `1.jpg`).

## Architecture

**Everything is duplicated per division (Mr / Ms) and per category.** There's no generic category/criteria model. Each category has its own table, model, Livewire component, judge view, report view, and routes, in `Mr_*` and `Ms_*` pairs. A change to one category usually has to be repeated in its sibling (and often across all categories).

**Stages and categories** (see `StageSeeder`; the `stages.is_active` flag controls which stage judges see on `/judge-app`):
- Pre-Pageant: talent, rave wear → aggregate `*_prepageant_scores`
- Preliminaries: national costume, departmental uniform, swim wear, formal wear, Q&A → aggregate `*_prelim_scores`
- Final: `*_final_scores` (wit, projection, stage presence, overall impact), only for candidates with `is_active = 1`

**Scoring flow:** Score rows are pre-created with null criteria by seeders (one row per candidate × judge). Judges then fill them in. Each judge Livewire component (`app/Http/Livewire/Judge/{Prepageant,Preliminaries,Final}/{Mr,Ms}/*ScoreBoardComponent.php`) does the following:
- In `updatedRecords()`, it saves the raw criteria to the category table on every change. It also writes a weighted value into the stage's aggregate table: `(criteria total / 100) * weight`, e.g. talent ×50 and each prelim category ×20.
- In `confirmedLockInScores()`, it validates per-criterion maximums (hard-coded in the component) and sets `is_lock = 1`.
- It uses SweetAlert through `$this->dispatch('swal:*', type: ..., message: ..., text: ...)`. Those named args become `event.detail` in the `window.addEventListener` handlers that each view pushes to `@stack('scripts')`. The confirm dialogs call back with `Livewire.dispatch('confirmedLockInScores')`, which is handled by `$listeners`.

**Livewire 4 compatibility settings** (`config/livewire.php`):
- `class_namespace` is `App\Http\Livewire`, the Livewire 2 location.
- `legacy_model_binding` is `true`. The scoreboards bind inputs straight to Eloquent collections (`wire:model.live="records.{{ $index }}.mastery"`), which needs this flag plus a `$rules` entry for every bound field.
- Score inputs use `wire:model.live`. Plain `wire:model` no longer saves until a request is sent, so `updatedRecords()` would stop saving as the judge types.

**Rankings** are computed on demand, not live. The admin clicks buttons on the report pages, and those buttons make AJAX GET calls to the `*_rank` routes in the `Mr/MsUep{PrePageant,Prelim,Final}ReportsController`. Each call does two things:
1. It ranks candidates per judge by aggregate score and writes the result to a column of `*_rankings`. Ties share a rank.
2. It sums the per-judge ranks into `*_final_ranks`, where the lowest sum wins.
`mr/ms_to_top_5_rank` flips `*_candidates.is_active` for the finalists. `mr/ms_final_score_seeder` routes run `Mr/MsFinalScoreSeeder` from the browser to create final score rows for those finalists.

**Judges are hard-coded as user IDs 2–4** (`for ($x = 2; $x <= 4; $x++)`, `whereIn('id', range(2, 4))` in seeders), and reports have fixed `judge1/2/3` views and routes. Adding or reordering judges requires changing these loops, seeders, and views.

**Auth:** `users.role` is `admin` or `judge`. Access is enforced by the `user-access:<role>` middleware (`app/Http/Middleware/UserAccess.php`). All admin routes and all judge routes (`/judge-app/...`) are two groups in `routes/web.php`. spatie/laravel-permission is installed but not used for access checks.

**PDFs** use barryvdh/laravel-dompdf: `PDF::loadView('admin.reports....pdf*')->setPaper([0,0,612,936], 'landscape')->stream()`. Each report has an HTML view plus `pdf*` views, per judge and combined.

## Gotchas

- Controllers reference views in lowercase (`admin.reports.prelim.mr...`), but the directories are capitalized (`resources/views/admin/reports/Prelim/Mr`). This only works on case-insensitive filesystems like macOS, so it breaks on Linux.
- Leftovers from an earlier "Miss Binalonan" project are still in the code and would fail if hit: `barangays` routes/views, `FinalScoreGenerator` command, `ReportsController` (`put*` routes), and `Stage::categories()`. `BarangayController`, `Category`, `Barangay`, `FinalScore`, and `SemifinalScore` don't exist. The old `Judge/ScoreBoardComponent`, `TalentScoreBoardComponent`, `FinalScoreBoardComponent`, and `Semifinal/` components are only reached from unrouted `judge_app/score-board-screen.blade.php` or from stage branches of the current screens that aren't used.
- `cal_percentage()` is a global helper in `app/helpers.php` (Composer `files` autoload). Don't declare PHP functions inside Blade `@php` blocks: re-rendering the view in one process, as Livewire tests do, fatals on redeclare.
- `DatabaseSeeder` seeds only users, stages, candidates, rankings, and pre-pageant scores. Prelim and final score rows come from `PrelimSeeder` and the final-score-seeder routes.
