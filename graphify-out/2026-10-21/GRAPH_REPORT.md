# Graph Report - pageant_tabulator  (2026-10-21)

## Corpus Check
- 133 files · ~1,647,141 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 610 nodes · 1083 edges · 84 communities (23 shown, 20 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 14 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `8c52f6c1`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- ScoreSheetTest
- score-sheet.blade.php
- composer.json
- pdf/round1.blade.php
- tables/round1.blade.php
- package.json
- tables/segment.blade.php
- pdf/segment.blade.php
- Candidate
- Illuminate\Database\Seeder
- results/round1.blade.php
- Tabulator
- judge.blade.php
- ScoreSheet
- CLAUDE.md
- Segment
- CandidateController
- README.md
- AppServiceProvider
- 0001_01_01_000000_create_users_table.php
- announcement.blade.php
- ScoreReset
- Announcement
- Illuminate\Http\Request
- winner.blade.php
- Illuminate\Support\Str
- EventServiceProvider.php
- Kernel
- Handler
- candidates/create.blade.php
- Http/Kernel.php
- TrustHosts
- AuthServiceProvider
- logging.php
- candidates/edit.blade.php
- config/app.php
- master.blade.php
- app.js
- judge_app/layouts/app.blade.php
- create.blade.php
- edit.blade.php
- Design: Mr. & Ms. LCUAA 2026 Tabulation
- views/layouts/app.blade.php

## God Nodes (most connected - your core abstractions)
1. `Candidate` - 70 edges
2. `Segment` - 65 edges
3. `User` - 59 edges
4. `Score` - 50 edges
5. `ScoreLock` - 37 edges
6. `Tabulator` - 34 edges
7. `PagesTest` - 29 edges
8. `TabulatorTest` - 25 edges
9. `OpenSegment` - 23 edges
10. `ScoreSheet` - 20 edges

## Surprising Connections (you probably didn't know these)
- `AnnouncementTest` --references--> `User`  [EXTRACTED]
  tests/Feature/AnnouncementTest.php → app/Models/User.php
- `ScoreSheetTest` --references--> `User`  [EXTRACTED]
  tests/Feature/ScoreSheetTest.php → app/Models/User.php
- `PagesTest` --references--> `User`  [EXTRACTED]
  tests/Feature/PagesTest.php → app/Models/User.php
- `ScoreResetTest` --references--> `User`  [EXTRACTED]
  tests/Feature/ScoreResetTest.php → app/Models/User.php
- `CandidateController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/CandidateController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (84 total, 20 thin omitted)

### Community 1 - "score-sheet.blade.php"
Cohesion: 0.50
Nodes (3): livewire.judge.card, lock, showView(

### Community 2 - "composer.json"
Cohesion: 0.05
Nodes (43): pestphp/pest-plugin, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins, optimize-autoloader (+35 more)

### Community 5 - "package.json"
Cohesion: 0.11
Nodes (18): devDependencies, @fontsource-variable/ibm-plex-sans, @fortawesome/fontawesome-free, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, private (+10 more)

### Community 8 - "Candidate"
Cohesion: 0.06
Nodes (21): HomeController, SegmentController, Candidate, OpenSegment, Score, ScoreLock, User, Barryvdh\DomPDF\Facade\Pdf (+13 more)

### Community 9 - "Illuminate\Database\Seeder"
Cohesion: 0.27
Nodes (4): CandidateSeeder, DatabaseSeeder, UserSeeder, Illuminate\Database\Seeder

### Community 11 - "Tabulator"
Cohesion: 0.13
Nodes (5): Tabulator, PHPUnit\Framework\Attributes\DataProvider, PHPUnit\Framework\TestCase, TabulatorTest, ScoringRulesTest

### Community 14 - "ScoreSheet"
Cohesion: 0.13
Nodes (5): ScoreSheet, Points, Livewire\Attributes\Computed, Livewire\Attributes\Locked, Livewire\Component

### Community 19 - "CLAUDE.md"
Cohesion: 0.22
Nodes (7): Architecture, Commands, Gotchas, Scoring rules, Serving, UI, What this is

### Community 20 - "Segment"
Cohesion: 0.09
Nodes (8): Segment, Illuminate\Contracts\Console\Kernel, Illuminate\Foundation\Testing\TestCase, Illuminate\Support\Collection, self, CreatesApplication, ScoringConfigTest, TestCase

### Community 21 - "CandidateController"
Cohesion: 0.16
Nodes (7): CandidateController, Illuminate\Foundation\Inspiring, Illuminate\Http\UploadedFile, Illuminate\Support\Facades\Artisan, Illuminate\Support\Facades\File, Illuminate\Validation\ValidationException, UploadedFile

### Community 22 - "README.md"
Cohesion: 0.29
Nodes (6): Accounts, Candidate photos, Login artwork, migrate tables and initialize everything, running it for the event (tablets on the venue WLAN, no internet), Scoring

### Community 27 - "AppServiceProvider"
Cohesion: 0.24
Nodes (4): AppServiceProvider, BroadcastServiceProvider, Illuminate\Support\Facades\Broadcast, Illuminate\Support\ServiceProvider

### Community 28 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.23
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 31 - "ScoreReset"
Cohesion: 0.16
Nodes (6): ClearScores, ResizeCandidatePhotos, ServeForEvent, ScoreResetController, ScoreReset, Illuminate\Console\Command

### Community 33 - "Illuminate\Http\Request"
Cohesion: 0.05
Nodes (28): ConfirmPasswordController, LoginController, Controller, JudgeAppController, ResultsController, UserController, RedirectIfAuthenticated, UserAccess (+20 more)

### Community 36 - "Illuminate\Support\Str"
Cohesion: 0.29
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str

### Community 39 - "EventServiceProvider.php"
Cohesion: 0.25
Nodes (5): EventServiceProvider, Illuminate\Auth\Events\Registered, Illuminate\Auth\Listeners\SendEmailVerificationNotification, Illuminate\Foundation\Support\Providers\EventServiceProvider, Illuminate\Support\Facades\Event

### Community 43 - "Kernel"
Cohesion: 0.40
Nodes (3): Kernel, Illuminate\Console\Scheduling\Schedule, Illuminate\Foundation\Console\Kernel

### Community 44 - "Handler"
Cohesion: 0.40
Nodes (3): Handler, Illuminate\Foundation\Exceptions\Handler, Throwable

### Community 50 - "Http/Kernel.php"
Cohesion: 0.05
Nodes (30): Kernel, Authenticate, EncryptCookies, PreventRequestsDuringMaintenance, TrimStrings, TrustProxies, ValidateSignature, VerifyCsrfToken (+22 more)

### Community 53 - "logging.php"
Cohesion: 0.50
Nodes (3): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler

### Community 132 - "app.js"
Cohesion: 0.26
Nodes (7): pollSegments(), pollTables(), refreshTables(), request(), setLiveNote(), SignedOutError, swapRegion()

### Community 287 - "Design: Mr. & Ms. LCUAA 2026 Tabulation"
Cohesion: 0.50
Nodes (3): Decisions and reasons, Design: Mr. & Ms. LCUAA 2026 Tabulation, Identity motif

## Knowledge Gaps
- **82 isolated node(s):** `name`, `type`, `description`, `keywords`, `license` (+77 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 257 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **20 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Candidate` connect `Candidate` to `Announcement`, `Illuminate\Http\Request`, `ScoreSheetTest`, `Illuminate\Database\Seeder`, `Tabulator`, `ScoreSheet`, `Segment`, `CandidateController`?**
  _High betweenness centrality (0.084) - this node is a cross-community bridge._
- **Why does `User` connect `Candidate` to `Announcement`, `Illuminate\Http\Request`, `ScoreSheetTest`, `Illuminate\Database\Seeder`, `Tabulator`, `Segment`?**
  _High betweenness centrality (0.065) - this node is a cross-community bridge._
- **Why does `Segment` connect `Segment` to `Illuminate\Http\Request`, `Candidate`, `Tabulator`, `ScoreSheet`, `ScoreReset`?**
  _High betweenness centrality (0.064) - this node is a cross-community bridge._
- **What connects `name`, `type`, `description` to the rest of the system?**
  _82 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.045454545454545456 - nodes in this community are weakly interconnected._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.11052631578947368 - nodes in this community are weakly interconnected._
- **Should `Candidate` be split into smaller, more focused modules?**
  _Cohesion score 0.05757286192068801 - nodes in this community are weakly interconnected._