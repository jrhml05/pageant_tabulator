# Graph Report - pageant_tabulator  (2026-09-25)

## Corpus Check
- 421 files · ~1,683,910 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1216 nodes · 2155 edges · 351 communities (29 shown, 73 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 16 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `c94121c1`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Ms_candidate
- Ms_formalwear_score
- composer.json
- JudgeAppController
- Mr_candidate
- package.json
- sweet-alert.js
- Mr_talent_score
- Illuminate\Database\Eloquent\Relations\HasMany
- TestCase
- Ms_prepageant_score
- User
- Illuminate\Database\Eloquent\Factories\HasFactory
- Mr_deptuni_score
- Controller
- Illuminate\Http\Request
- Ms_deptuni_score
- Mr_ravewear_score
- Illuminate\Database\Seeder
- CLAUDE.md
- ActiveStatusComponent
- Mr_swimwear_score
- README.md
- Mr_formalwear_score
- Mr_natlcost_score
- Mr_qna_score
- Livewire\Component
- AppServiceProvider
- Illuminate\Database\Schema\Blueprint
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Console\Seeds\WithoutModelEvents
- ResizeCandidatePhotos
- RouteServiceProvider
- Mr_final_score
- Ms_final_score
- ExampleTest
- UserFactory
- ConfirmPasswordController.php
- AdminUserSeeder.php
- EventServiceProvider.php
- RouteServiceProvider.php
- Illuminate\Database\Migrations\Migration
- RegisterController.php
- Kernel
- Handler
- LoginController.php
- MrRavewearScoreSeeder.php
- MsDeptUniScoreSeeder.php
- MsTalentScoreSeeder.php
- Authenticate
- TrustHosts
- AuthServiceProvider
- logging.php
- Kernel
- EncryptCookies
- PreventRequestsDuringMaintenance
- TrimStrings
- ValidateSignature
- VerifyCsrfToken
- MrDeptUniScoreSeeder.php
- MrFinalScoreSeeder.php
- MrNatlCostScoreSeeder.php
- MrPrelimScoreSeeder.php
- MrPrepageantScoreSeeder.php
- MrSwimWearScoreSeeder.php
- MsFinalScoreSeeder.php
- MsNatlCostScoreSeeder.php
- MsPrepageantScoreSeeder.php
- final/mr/score-board-component.blade.php
- final/ms/score-board-component.blade.php
- mr/departmentaluniform-score-board-component.blade.php
- mr/formalwear-score-board-component.blade.php
- mr/nationalcostume-score-board-component.blade.php
- mr/qna-score-board-component.blade.php
- mr/swimwear-score-board-component.blade.php
- ms/departmentaluniform-score-board-component.blade.php
- ms/formalwear-score-board-component.blade.php
- ms/nationalcostume-score-board-component.blade.php
- ms/qna-score-board-component.blade.php
- ms/swimwear-score-board-component.blade.php
- mr/ravewear-score-board-component.blade.php
- mr/talent-score-board-component.blade.php
- ms/ravewear-score-board-component.blade.php
- ms/talent-score-board-component.blade.php
- config/app.php
- sanctum.php
- master.blade.php
- app.js
- judge_app/layouts/app.blade.php
- Ms_natlcost_score
- Ms_qna_score
- create.blade.php
- edit.blade.php
- Ms_swimwear_score
- StageControllerComponent
- HomeController.php
- Design: Mr. & Ms. LCUAA 2026 Tabulation
- MrFormalWearScoreSeeder.php
- MrQnaScoreSeeder.php
- Illuminate\Support\Facades\Artisan
- web.php
- views/layouts/app.blade.php

## God Nodes (most connected - your core abstractions)
1. `Mr_candidate` - 126 edges
2. `Ms_candidate` - 125 edges
3. `Mr_ranking` - 95 edges
4. `Ms_ranking` - 95 edges
5. `User` - 64 edges
6. `MrLcuaaPrelimReportsController` - 59 edges
7. `MsLcuaaPrelimReportsController` - 59 edges
8. `Mr_final_rank` - 42 edges
9. `Ms_final_rank` - 42 edges
10. `MrLcuaaPrePageantReportsController` - 29 edges

## Surprising Connections (you probably didn't know these)
- `ConfirmPasswordController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/ConfirmPasswordController.php → app/Http/Controllers/Controller.php
- `LoginController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/LoginController.php → app/Http/Controllers/Controller.php
- `RegisterController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/RegisterController.php → app/Http/Controllers/Controller.php
- `VerificationController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/VerificationController.php → app/Http/Controllers/Controller.php
- `HomeController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/HomeController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (351 total, 73 thin omitted)

### Community 0 - "Ms_candidate"
Cohesion: 0.06
Nodes (8): MsLcuaaFinalReportsController, MsLcuaaPrelimReportsController, MsLcuaaPrePageantReportsController, Ms_candidate, Ms_final_rank, Ms_ranking, MsRankingSeeder, PDF

### Community 2 - "composer.json"
Cohesion: 0.04
Nodes (46): pestphp/pest-plugin, autoload, autoload-dev, psr-4, files, psr-4, config, allow-plugins (+38 more)

### Community 4 - "Mr_candidate"
Cohesion: 0.06
Nodes (9): MrLcuaaFinalReportsController, MrLcuaaPrelimReportsController, MrLcuaaPrePageantReportsController, Mr_candidate, Mr_final_rank, Mr_ranking, MrRankingSeeder, Illuminate\Support\Facades\DB (+1 more)

### Community 5 - "package.json"
Cohesion: 0.11
Nodes (18): devDependencies, @fontsource-variable/ibm-plex-sans, @fortawesome/fontawesome-free, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, private (+10 more)

### Community 6 - "sweet-alert.js"
Cohesion: 0.53
Nodes (11): a(), c(), e(), f(), i(), l(), n(), o() (+3 more)

### Community 9 - "TestCase"
Cohesion: 0.22
Nodes (5): Illuminate\Contracts\Console\Kernel, Illuminate\Foundation\Testing\TestCase, CreatesApplication, ExampleTest, TestCase

### Community 10 - "Ms_prepageant_score"
Cohesion: 0.13
Nodes (6): RavewearScoreBoardComponent, ScoreBoardComponent, TalentScoreBoardComponent, Ms_prepageant_score, Ms_ravewear_score, Ms_talent_score

### Community 11 - "User"
Cohesion: 0.15
Nodes (7): UserController, User, MsPrelimScoreSeeder, MsQnaScoreSeeder, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Laravel\Sanctum\HasApiTokens

### Community 12 - "Illuminate\Database\Eloquent\Factories\HasFactory"
Cohesion: 0.13
Nodes (3): Stage, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model

### Community 14 - "Controller"
Cohesion: 0.20
Nodes (10): ForgotPasswordController, ResetPasswordController, CandidateController, Controller, Illuminate\Foundation\Auth\Access\AuthorizesRequests, Illuminate\Foundation\Auth\ResetsPasswords, Illuminate\Foundation\Auth\SendsPasswordResetEmails, Illuminate\Foundation\Bus\DispatchesJobs (+2 more)

### Community 15 - "Illuminate\Http\Request"
Cohesion: 0.20
Nodes (7): RedirectIfAuthenticated, TrustProxies, UserAccess, Closure, Illuminate\Contracts\Http\Kernel, Illuminate\Http\Middleware\TrustProxies, Illuminate\Http\Request

### Community 18 - "Illuminate\Database\Seeder"
Cohesion: 0.13
Nodes (8): DatabaseSeeder, MrCandidateSeeder, MrFinalRankingSeeder, MsCandidateSeeder, MsFormalWearScoreSeeder, PrelimSeeder, StageSeeder, Illuminate\Database\Seeder

### Community 19 - "CLAUDE.md"
Cohesion: 0.25
Nodes (6): Architecture, Commands, Gotchas, Serving, UI, What this is

### Community 22 - "README.md"
Cohesion: 0.29
Nodes (6): 3 Judges (sample) you can set new, Admin, Candidate photos, Login artwork, migrate tables and initialize everything, running it for the event (tablets on the venue WLAN, no internet)

### Community 26 - "Livewire\Component"
Cohesion: 0.14
Nodes (8): ScoreBoardComponent, ScoreBoardComponent, ScoreBoardComponent, Mr_prelim_score, Mr_prepageant_score, Ms_prelim_score, Illuminate\Support\Facades\Auth, Livewire\Component

### Community 27 - "AppServiceProvider"
Cohesion: 0.24
Nodes (4): AppServiceProvider, BroadcastServiceProvider, Illuminate\Support\Facades\Broadcast, Illuminate\Support\ServiceProvider

### Community 30 - "Illuminate\Database\Console\Seeds\WithoutModelEvents"
Cohesion: 0.17
Nodes (5): MrTalentScoreSeeder, MsFinalRankingSeeder, MsRavewearScoreSeeder, MsSwimWearScoreSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents

### Community 31 - "ResizeCandidatePhotos"
Cohesion: 0.33
Nodes (3): ResizeCandidatePhotos, ServeForEvent, Illuminate\Console\Command

### Community 32 - "RouteServiceProvider"
Cohesion: 0.32
Nodes (4): VerificationController, RouteServiceProvider, Illuminate\Foundation\Auth\VerifiesEmails, Illuminate\Foundation\Support\Providers\RouteServiceProvider

### Community 36 - "UserFactory"
Cohesion: 0.25
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str

### Community 38 - "AdminUserSeeder.php"
Cohesion: 0.25
Nodes (3): AdminUserSeeder, JudgeSeeder, Illuminate\Support\Facades\Hash

### Community 39 - "EventServiceProvider.php"
Cohesion: 0.25
Nodes (5): EventServiceProvider, Illuminate\Auth\Events\Registered, Illuminate\Auth\Listeners\SendEmailVerificationNotification, Illuminate\Foundation\Support\Providers\EventServiceProvider, Illuminate\Support\Facades\Event

### Community 40 - "RouteServiceProvider.php"
Cohesion: 0.40
Nodes (3): Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\Facades\Route

### Community 42 - "RegisterController.php"
Cohesion: 0.33
Nodes (3): RegisterController, Illuminate\Foundation\Auth\RegistersUsers, Illuminate\Support\Facades\Validator

### Community 43 - "Kernel"
Cohesion: 0.40
Nodes (3): Kernel, Illuminate\Console\Scheduling\Schedule, Illuminate\Foundation\Console\Kernel

### Community 44 - "Handler"
Cohesion: 0.40
Nodes (3): Handler, Illuminate\Foundation\Exceptions\Handler, Throwable

### Community 53 - "logging.php"
Cohesion: 0.50
Nodes (3): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler

### Community 132 - "app.js"
Cohesion: 0.29
Nodes (7): pollTables(), refreshTables(), request(), restoreButton(), runAction(), setLiveNote(), SignedOutError

### Community 287 - "Design: Mr. & Ms. LCUAA 2026 Tabulation"
Cohesion: 0.50
Nodes (3): Decisions and reasons, Design: Mr. & Ms. LCUAA 2026 Tabulation, Identity motif

## Knowledge Gaps
- **84 isolated node(s):** `name`, `type`, `description`, `keywords`, `license` (+79 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 547 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **73 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Mr_candidate` connect `Mr_candidate` to `MrFormalWearScoreSeeder.php`, `MrQnaScoreSeeder.php`, `MrSwimWearScoreSeeder.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `Controller`, `MrRavewearScoreSeeder.php`, `Illuminate\Database\Seeder`, `Illuminate\Database\Console\Seeds\WithoutModelEvents`, `MrDeptUniScoreSeeder.php`, `MrFinalScoreSeeder.php`, `MrNatlCostScoreSeeder.php`, `MrPrelimScoreSeeder.php`, `MrPrepageantScoreSeeder.php`, `HomeController.php`?**
  _High betweenness centrality (0.054) - this node is a cross-community bridge._
- **Why does `Ms_candidate` connect `Ms_candidate` to `MsFinalScoreSeeder.php`, `MsNatlCostScoreSeeder.php`, `MsPrepageantScoreSeeder.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `User`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `Controller`, `MsDeptUniScoreSeeder.php`, `MsTalentScoreSeeder.php`, `Illuminate\Database\Seeder`, `Illuminate\Database\Console\Seeds\WithoutModelEvents`, `HomeController.php`?**
  _High betweenness centrality (0.044) - this node is a cross-community bridge._
- **Why does `Controller` connect `Controller` to `RouteServiceProvider`, `Ms_candidate`, `JudgeAppController`, `Mr_candidate`, `ConfirmPasswordController.php`, `RegisterController.php`, `User`, `LoginController.php`, `HomeController.php`?**
  _High betweenness centrality (0.044) - this node is a cross-community bridge._
- **What connects `name`, `type`, `description` to the rest of the system?**
  _84 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Ms_candidate` be split into smaller, more focused modules?**
  _Cohesion score 0.06325247079964061 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.0425531914893617 - nodes in this community are weakly interconnected._
- **Should `JudgeAppController` be split into smaller, more focused modules?**
  _Cohesion score 0.09090909090909091 - nodes in this community are weakly interconnected._