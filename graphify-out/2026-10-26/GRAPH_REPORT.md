# Graph Report - pageant_tabulator  (2026-10-26)

## Corpus Check
- 137 files · ~1,661,220 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 658 nodes · 1242 edges · 94 communities (28 shown, 23 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 13 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `91937302`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Controller
- score-sheet.blade.php
- composer.json
- pdf/round1.blade.php
- tables/round1.blade.php
- package.json
- tables/segment.blade.php
- pdf/segment.blade.php
- User
- Illuminate\Database\Seeder
- results/round1.blade.php
- Tabulator
- judge.blade.php
- web.php
- results/segment.blade.php
- CLAUDE.md
- Segment
- Illuminate\Http\Request
- README.md
- AppServiceProvider
- 0001_01_01_000000_create_users_table.php
- announcement.blade.php
- Illuminate\Console\Command
- ResultsController
- rank({{ $candidate->id }}, {{ $r }})
- Score
- Illuminate\Support\Str
- EventServiceProvider.php
- CandidateController
- LoginController
- Kernel
- Handler
- Candidate
- candidates/create.blade.php
- Http/Kernel.php
- TrustHosts
- AuthServiceProvider
- logging.php
- CandidateController.php
- UserController
- RouteServiceProvider
- winners.blade.php
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
1. `Candidate` - 86 edges
2. `Segment` - 85 edges
3. `User` - 64 edges
4. `Score` - 60 edges
5. `ScoreLock` - 40 edges
6. `Tabulator` - 38 edges
7. `PagesTest` - 36 edges
8. `ScoreSheetTest` - 32 edges
9. `TabulatorTest` - 30 edges
10. `ScoreSheet` - 26 edges

## Surprising Connections (you probably didn't know these)
- `AnnouncementTest` --references--> `User`  [EXTRACTED]
  tests/Feature/AnnouncementTest.php → app/Models/User.php
- `ScoreResetTest` --references--> `User`  [EXTRACTED]
  tests/Feature/ScoreResetTest.php → app/Models/User.php
- `ScoreSheetTest` --references--> `User`  [EXTRACTED]
  tests/Feature/ScoreSheetTest.php → app/Models/User.php
- `PagesTest` --references--> `User`  [EXTRACTED]
  tests/Feature/PagesTest.php → app/Models/User.php
- `LoginController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/LoginController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (94 total, 23 thin omitted)

### Community 0 - "Controller"
Cohesion: 0.31
Nodes (7): ConfirmPasswordController, Controller, Illuminate\Foundation\Auth\Access\AuthorizesRequests, Illuminate\Foundation\Auth\ConfirmsPasswords, Illuminate\Foundation\Bus\DispatchesJobs, Illuminate\Foundation\Validation\ValidatesRequests, Illuminate\Routing\Controller

### Community 1 - "score-sheet.blade.php"
Cohesion: 0.50
Nodes (3): clearRanking, lock, showView(

### Community 2 - "composer.json"
Cohesion: 0.05
Nodes (43): pestphp/pest-plugin, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins, optimize-autoloader (+35 more)

### Community 3 - "pdf/round1.blade.php"
Cohesion: 0.50
Nodes (3): admin.results.pdf.signatures, admin.results.round1-method, admin.results.tables.round1

### Community 5 - "package.json"
Cohesion: 0.11
Nodes (18): devDependencies, @fontsource-variable/ibm-plex-sans, @fortawesome/fontawesome-free, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, private (+10 more)

### Community 8 - "User"
Cohesion: 0.07
Nodes (11): User, Illuminate\Contracts\Console\Kernel, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Foundation\Auth\User, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, Illuminate\Notifications\Notifiable, Livewire\Livewire (+3 more)

### Community 9 - "Illuminate\Database\Seeder"
Cohesion: 0.27
Nodes (4): CandidateSeeder, DatabaseSeeder, UserSeeder, Illuminate\Database\Seeder

### Community 11 - "Tabulator"
Cohesion: 0.13
Nodes (5): Tabulator, PHPUnit\Framework\Attributes\DataProvider, PHPUnit\Framework\TestCase, TabulatorTest, ScoringRulesTest

### Community 19 - "CLAUDE.md"
Cohesion: 0.22
Nodes (7): Architecture, Commands, Gotchas, Scoring rules, Serving, UI, What this is

### Community 20 - "Segment"
Cohesion: 0.06
Nodes (9): ScoreSheet, Points, Segment, Illuminate\Support\Collection, Livewire\Attributes\Computed, Livewire\Attributes\Locked, Livewire\Component, self (+1 more)

### Community 21 - "Illuminate\Http\Request"
Cohesion: 0.36
Nodes (3): JudgeAppController, Illuminate\Contracts\Http\Kernel, Illuminate\Http\Request

### Community 22 - "README.md"
Cohesion: 0.29
Nodes (6): Accounts, Candidate photos, Login artwork, migrate tables and initialize everything, running it for the event (tablets on the venue WLAN, no internet), Scoring

### Community 27 - "AppServiceProvider"
Cohesion: 0.24
Nodes (4): AppServiceProvider, BroadcastServiceProvider, Illuminate\Support\Facades\Broadcast, Illuminate\Support\ServiceProvider

### Community 28 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.23
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 31 - "Illuminate\Console\Command"
Cohesion: 0.24
Nodes (4): ClearScores, ResizeCandidatePhotos, ServeForEvent, Illuminate\Console\Command

### Community 32 - "ResultsController"
Cohesion: 0.14
Nodes (3): ResultsController, Announcement, AnnouncementTest

### Community 34 - "Score"
Cohesion: 0.08
Nodes (12): ScoreResetController, SegmentController, OpenSegment, Score, ScoreLock, ScoreReset, Barryvdh\DomPDF\Facade\Pdf, Illuminate\Database\Eloquent\Model (+4 more)

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

### Community 45 - "Candidate"
Cohesion: 0.13
Nodes (4): Candidate, Illuminate\Database\Eloquent\Builder, Illuminate\Database\Eloquent\Relations\HasMany, ScoreSheetTest

### Community 50 - "Http/Kernel.php"
Cohesion: 0.04
Nodes (36): Kernel, Authenticate, EncryptCookies, PreventRequestsDuringMaintenance, RedirectIfAuthenticated, TrimStrings, TrustProxies, UserAccess (+28 more)

### Community 53 - "logging.php"
Cohesion: 0.50
Nodes (3): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler

### Community 56 - "CandidateController.php"
Cohesion: 0.20
Nodes (7): Illuminate\Foundation\Inspiring, Illuminate\Http\UploadedFile, Illuminate\Support\Facades\Artisan, Illuminate\Support\Facades\File, Illuminate\Support\Facades\Hash, Illuminate\Validation\Rule, Illuminate\Validation\ValidationException

### Community 60 - "RouteServiceProvider"
Cohesion: 0.29
Nodes (5): RouteServiceProvider, Illuminate\Cache\RateLimiting\Limit, Illuminate\Foundation\Support\Providers\RouteServiceProvider, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\Facades\Route

### Community 132 - "app.js"
Cohesion: 0.26
Nodes (7): pollSegments(), pollTables(), refreshTables(), request(), setLiveNote(), SignedOutError, swapRegion()

### Community 287 - "Design: Mr. & Ms. LCUAA 2026 Tabulation"
Cohesion: 0.50
Nodes (3): Decisions and reasons, Design: Mr. & Ms. LCUAA 2026 Tabulation, Identity motif

## Knowledge Gaps
- **87 isolated node(s):** `name`, `type`, `description`, `keywords`, `license` (+82 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 261 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **23 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Candidate` connect `Candidate` to `ResultsController`, `Score`, `User`, `CandidateController`, `Illuminate\Database\Seeder`, `Tabulator`, `web.php`, `Segment`, `CandidateController.php`?**
  _High betweenness centrality (0.095) - this node is a cross-community bridge._
- **Why does `Segment` connect `Segment` to `ResultsController`, `Score`, `User`, `Tabulator`, `web.php`, `Illuminate\Http\Request`?**
  _High betweenness centrality (0.084) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `ResultsController`, `Score`, `Illuminate\Database\Seeder`, `Tabulator`, `Candidate`, `web.php`, `Segment`, `CandidateController.php`, `UserController`?**
  _High betweenness centrality (0.065) - this node is a cross-community bridge._
- **What connects `name`, `type`, `description` to the rest of the system?**
  _87 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.045454545454545456 - nodes in this community are weakly interconnected._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.11052631578947368 - nodes in this community are weakly interconnected._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.07446808510638298 - nodes in this community are weakly interconnected._