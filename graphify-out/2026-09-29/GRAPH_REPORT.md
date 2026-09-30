# Graph Report - pageant_tabulator  (2026-09-29)

## Corpus Check
- 128 files · ~1,639,603 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 543 nodes · 824 edges · 83 communities (26 shown, 17 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 7 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `f4777c20`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Segment
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
- TestCase
- judge.blade.php
- User
- Controller
- CLAUDE.md
- ScoreSheet
- CandidateController
- README.md
- AppServiceProvider
- 0001_01_01_000000_create_users_table.php
- announcement.blade.php
- ResizeCandidatePhotos
- Illuminate\Http\Request
- Tabulator
- UserFactory
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
1. `Candidate` - 52 edges
2. `User` - 43 edges
3. `Segment` - 37 edges
4. `Score` - 25 edges
5. `ScoreLock` - 20 edges
6. `PagesTest` - 19 edges
7. `ScoreSheet` - 18 edges
8. `ScoreSheetTest` - 18 edges
9. `OpenSegment` - 17 edges
10. `Tabulator` - 17 edges

## Surprising Connections (you probably didn't know these)
- `AnnouncementTest` --references--> `User`  [EXTRACTED]
  tests/Feature/AnnouncementTest.php → app/Models/User.php
- `ScoreSheetTest` --references--> `User`  [EXTRACTED]
  tests/Feature/ScoreSheetTest.php → app/Models/User.php
- `PagesTest` --references--> `User`  [EXTRACTED]
  tests/Feature/PagesTest.php → app/Models/User.php
- `CandidateController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/CandidateController.php → app/Http/Controllers/Controller.php
- `HomeController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/HomeController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (83 total, 17 thin omitted)

### Community 0 - "Segment"
Cohesion: 0.10
Nodes (5): SegmentController, Segment, Illuminate\Support\Collection, self, ScoringConfigTest

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
Cohesion: 0.11
Nodes (10): ClearScores, Candidate, OpenSegment, Score, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Support\Facades\DB (+2 more)

### Community 9 - "Illuminate\Database\Seeder"
Cohesion: 0.38
Nodes (3): CandidateSeeder, DatabaseSeeder, Illuminate\Database\Seeder

### Community 11 - "TestCase"
Cohesion: 0.14
Nodes (7): Illuminate\Contracts\Console\Kernel, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, CreatesApplication, AnnouncementTest, TabulatorTest, TestCase

### Community 14 - "User"
Cohesion: 0.08
Nodes (11): UserController, User, Barryvdh\DomPDF\Facade\Pdf, UserSeeder, Illuminate\Database\Eloquent\Builder, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable (+3 more)

### Community 15 - "Controller"
Cohesion: 0.15
Nodes (11): ConfirmPasswordController, LoginController, Controller, RouteServiceProvider, Illuminate\Foundation\Auth\Access\AuthorizesRequests, Illuminate\Foundation\Auth\AuthenticatesUsers, Illuminate\Foundation\Auth\ConfirmsPasswords, Illuminate\Foundation\Bus\DispatchesJobs (+3 more)

### Community 19 - "CLAUDE.md"
Cohesion: 0.22
Nodes (7): Architecture, Commands, Gotchas, Scoring rules, Serving, UI, What this is

### Community 20 - "ScoreSheet"
Cohesion: 0.11
Nodes (8): ScoreSheet, Points, Livewire\Attributes\Computed, Livewire\Attributes\Locked, Livewire\Component, PHPUnit\Framework\Attributes\DataProvider, PHPUnit\Framework\TestCase, ScoringRulesTest

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

### Community 31 - "ResizeCandidatePhotos"
Cohesion: 0.33
Nodes (3): ResizeCandidatePhotos, ServeForEvent, Illuminate\Console\Command

### Community 32 - "Illuminate\Http\Request"
Cohesion: 0.09
Nodes (15): HomeController, JudgeAppController, RedirectIfAuthenticated, UserAccess, ScoreLock, Closure, Illuminate\Cache\RateLimiting\Limit, Illuminate\Contracts\Http\Kernel (+7 more)

### Community 33 - "Tabulator"
Cohesion: 0.16
Nodes (3): ResultsController, Announcement, Tabulator

### Community 36 - "UserFactory"
Cohesion: 0.25
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
Cohesion: 0.27
Nodes (6): pollSegments(), pollTables(), refreshTables(), request(), setLiveNote(), SignedOutError

### Community 287 - "Design: Mr. & Ms. LCUAA 2026 Tabulation"
Cohesion: 0.50
Nodes (3): Decisions and reasons, Design: Mr. & Ms. LCUAA 2026 Tabulation, Identity motif

## Knowledge Gaps
- **81 isolated node(s):** `name`, `type`, `description`, `keywords`, `license` (+76 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 249 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **17 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Candidate` connect `Candidate` to `Illuminate\Http\Request`, `Tabulator`, `Segment`, `Illuminate\Database\Seeder`, `TestCase`, `User`, `ScoreSheet`, `CandidateController`?**
  _High betweenness centrality (0.081) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `Illuminate\Http\Request`, `Tabulator`, `Segment`, `Candidate`, `TestCase`?**
  _High betweenness centrality (0.062) - this node is a cross-community bridge._
- **Why does `Segment` connect `Segment` to `Illuminate\Http\Request`, `Tabulator`, `Candidate`, `TestCase`, `User`, `ScoreSheet`?**
  _High betweenness centrality (0.041) - this node is a cross-community bridge._
- **What connects `name`, `type`, `description` to the rest of the system?**
  _81 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Segment` be split into smaller, more focused modules?**
  _Cohesion score 0.10344827586206896 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.045454545454545456 - nodes in this community are weakly interconnected._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.11052631578947368 - nodes in this community are weakly interconnected._