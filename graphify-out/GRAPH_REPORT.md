# Graph Report - pageant_tabulator  (2026-09-25)

## Corpus Check
- 424 files · ~1,685,192 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1234 nodes · 2194 edges · 344 communities (34 shown, 60 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 16 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `94886a42`
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
- Mr_prepageant_score
- Illuminate\Database\Eloquent\Relations\HasMany
- TestCase
- Ms_prepageant_score
- User
- Illuminate\Database\Eloquent\Factories\HasFactory
- Mr_prelim_score
- Controller
- Illuminate\Http\Request
- Illuminate\Support\Facades\Auth
- Mr_ravewear_score
- Illuminate\Support\Facades\DB
- CLAUDE.md
- Livewire\Component
- CandidateController
- README.md
- Ms_ravewear_score
- MrTalentScoreSeeder.php
- MsFormalWearScoreSeeder.php
- MsPrelimScoreSeeder.php
- AppServiceProvider
- Illuminate\Database\Schema\Blueprint
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Seeder
- ResizeCandidatePhotos
- RouteServiceProvider
- Mr_final_score
- Ms_final_score
- ExampleTest
- UserFactory
- MsQnaScoreSeeder.php
- AdminUserSeeder.php
- EventServiceProvider.php
- web.php
- Illuminate\Database\Migrations\Migration
- RegisterController.php
- Kernel
- Handler
- LoginController.php
- MrRavewearScoreSeeder.php
- MsRavewearScoreSeeder.php
- candidates/create.blade.php
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
- candidates/edit.blade.php
- MrFinalScoreSeeder.php
- MrNatlCostScoreSeeder.php
- MrSwimWearScoreSeeder.php
- MsNatlCostScoreSeeder.php
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
- Design: Mr. & Ms. LCUAA 2026 Tabulation
- MrFormalWearScoreSeeder.php
- CandidateController.php
- views/layouts/app.blade.php

## God Nodes (most connected - your core abstractions)
1. `Mr_candidate` - 126 edges
2. `Ms_candidate` - 125 edges
3. `Mr_ranking` - 95 edges
4. `Ms_ranking` - 95 edges
5. `User` - 66 edges
6. `MrLcuaaPrelimReportsController` - 60 edges
7. `MsLcuaaPrelimReportsController` - 60 edges
8. `Mr_final_rank` - 42 edges
9. `Ms_final_rank` - 42 edges
10. `MrLcuaaPrePageantReportsController` - 30 edges

## Surprising Connections (you probably didn't know these)
- `LoginController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/LoginController.php → app/Http/Controllers/Controller.php
- `RegisterController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/RegisterController.php → app/Http/Controllers/Controller.php
- `VerificationController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/VerificationController.php → app/Http/Controllers/Controller.php
- `CandidateController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/CandidateController.php → app/Http/Controllers/Controller.php
- `HomeController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/HomeController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (344 total, 60 thin omitted)

### Community 0 - "Ms_candidate"
Cohesion: 0.06
Nodes (8): MsLcuaaFinalReportsController, MsLcuaaPrelimReportsController, MsLcuaaPrePageantReportsController, Ms_candidate, Ms_final_rank, Ms_ranking, PDF, PhpParser\Node\Expr\FuncCall

### Community 2 - "composer.json"
Cohesion: 0.04
Nodes (46): pestphp/pest-plugin, autoload, autoload-dev, psr-4, files, psr-4, config, allow-plugins (+38 more)

### Community 4 - "Mr_candidate"
Cohesion: 0.06
Nodes (8): MrLcuaaFinalReportsController, MrLcuaaPrelimReportsController, MrLcuaaPrePageantReportsController, Mr_candidate, Mr_final_rank, Mr_ranking, MrFinalRankingSeeder, MrRankingSeeder

### Community 5 - "package.json"
Cohesion: 0.11
Nodes (18): devDependencies, @fontsource-variable/ibm-plex-sans, @fortawesome/fontawesome-free, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, private (+10 more)

### Community 6 - "sweet-alert.js"
Cohesion: 0.53
Nodes (11): a(), c(), e(), f(), i(), l(), n(), o() (+3 more)

### Community 7 - "Mr_prepageant_score"
Cohesion: 0.17
Nodes (4): ScoreBoardComponent, TalentScoreBoardComponent, Mr_prepageant_score, Mr_talent_score

### Community 9 - "TestCase"
Cohesion: 0.22
Nodes (5): Illuminate\Contracts\Console\Kernel, Illuminate\Foundation\Testing\TestCase, CreatesApplication, ExampleTest, TestCase

### Community 10 - "Ms_prepageant_score"
Cohesion: 0.18
Nodes (4): ScoreBoardComponent, TalentScoreBoardComponent, Ms_prepageant_score, Ms_talent_score

### Community 11 - "User"
Cohesion: 0.15
Nodes (7): UserController, User, MrQnaScoreSeeder, MsPrepageantScoreSeeder, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Laravel\Sanctum\HasApiTokens

### Community 12 - "Illuminate\Database\Eloquent\Factories\HasFactory"
Cohesion: 0.17
Nodes (4): StageControllerComponent, Stage, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model

### Community 13 - "Mr_prelim_score"
Cohesion: 0.06
Nodes (12): DepartmentaluniformScoreBoardComponent, FormalwearScoreBoardComponent, NationalcostumeScoreBoardComponent, QnaScoreBoardComponent, SwimwearScoreBoardComponent, Mr_deptuni_score, Mr_formalwear_score, Mr_natlcost_score (+4 more)

### Community 14 - "Controller"
Cohesion: 0.20
Nodes (11): ConfirmPasswordController, ForgotPasswordController, ResetPasswordController, Controller, Illuminate\Foundation\Auth\Access\AuthorizesRequests, Illuminate\Foundation\Auth\ConfirmsPasswords, Illuminate\Foundation\Auth\ResetsPasswords, Illuminate\Foundation\Auth\SendsPasswordResetEmails (+3 more)

### Community 15 - "Illuminate\Http\Request"
Cohesion: 0.20
Nodes (7): RedirectIfAuthenticated, TrustProxies, UserAccess, Closure, Illuminate\Contracts\Http\Kernel, Illuminate\Http\Middleware\TrustProxies, Illuminate\Http\Request

### Community 16 - "Illuminate\Support\Facades\Auth"
Cohesion: 0.17
Nodes (5): DepartmentaluniformScoreBoardComponent, ScoreBoardComponent, Ms_deptuni_score, Ms_prelim_score, Illuminate\Support\Facades\Auth

### Community 18 - "Illuminate\Support\Facades\DB"
Cohesion: 0.14
Nodes (5): HomeController, DatabaseSeeder, PrelimSeeder, StageSeeder, Illuminate\Support\Facades\DB

### Community 19 - "CLAUDE.md"
Cohesion: 0.25
Nodes (6): Architecture, Commands, Gotchas, Serving, UI, What this is

### Community 20 - "Livewire\Component"
Cohesion: 0.28
Nodes (3): ActiveStatusComponent, ScoreBoardComponent, Livewire\Component

### Community 21 - "CandidateController"
Cohesion: 0.23
Nodes (3): CandidateController, Illuminate\Http\UploadedFile, UploadedFile

### Community 22 - "README.md"
Cohesion: 0.29
Nodes (6): 3 Judges (sample) you can set new, Admin, Candidate photos, Login artwork, migrate tables and initialize everything, running it for the event (tablets on the venue WLAN, no internet)

### Community 27 - "AppServiceProvider"
Cohesion: 0.24
Nodes (4): AppServiceProvider, BroadcastServiceProvider, Illuminate\Support\Facades\Broadcast, Illuminate\Support\ServiceProvider

### Community 30 - "Illuminate\Database\Seeder"
Cohesion: 0.11
Nodes (12): MrCandidateSeeder, MrDeptUniScoreSeeder, MrPrepageantScoreSeeder, MsCandidateSeeder, MsDeptUniScoreSeeder, MsFinalRankingSeeder, MsFinalScoreSeeder, MsRankingSeeder (+4 more)

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

### Community 40 - "web.php"
Cohesion: 0.25
Nodes (5): Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\Facades\Redirect, Illuminate\Support\Facades\Route, Illuminate\Support\Facades\Session

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

### Community 291 - "CandidateController.php"
Cohesion: 0.33
Nodes (4): Illuminate\Foundation\Inspiring, Illuminate\Support\Facades\Artisan, Illuminate\Support\Facades\File, Illuminate\Validation\ValidationException

## Knowledge Gaps
- **86 isolated node(s):** `name`, `type`, `description`, `keywords`, `license` (+81 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 557 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **60 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Mr_candidate` connect `Mr_candidate` to `MrFormalWearScoreSeeder.php`, `MrSwimWearScoreSeeder.php`, `CandidateController.php`, `Mr_prepageant_score`, `Illuminate\Database\Eloquent\Relations\HasMany`, `User`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `Mr_prelim_score`, `MrRavewearScoreSeeder.php`, `Illuminate\Support\Facades\DB`, `CandidateController`, `MrTalentScoreSeeder.php`, `MrFinalScoreSeeder.php`, `MrNatlCostScoreSeeder.php`, `Illuminate\Database\Seeder`?**
  _High betweenness centrality (0.054) - this node is a cross-community bridge._
- **Why does `Ms_candidate` connect `Ms_candidate` to `CandidateController.php`, `MsNatlCostScoreSeeder.php`, `MsQnaScoreSeeder.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `User`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `MsRavewearScoreSeeder.php`, `Illuminate\Support\Facades\DB`, `CandidateController`, `MsFormalWearScoreSeeder.php`, `MsPrelimScoreSeeder.php`, `Illuminate\Database\Seeder`?**
  _High betweenness centrality (0.052) - this node is a cross-community bridge._
- **Why does `Controller` connect `Controller` to `RouteServiceProvider`, `Ms_candidate`, `JudgeAppController`, `Mr_candidate`, `RegisterController.php`, `User`, `LoginController.php`, `Illuminate\Support\Facades\DB`, `CandidateController`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **What connects `name`, `type`, `description` to the rest of the system?**
  _86 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Ms_candidate` be split into smaller, more focused modules?**
  _Cohesion score 0.06428571428571428 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.0425531914893617 - nodes in this community are weakly interconnected._
- **Should `JudgeAppController` be split into smaller, more focused modules?**
  _Cohesion score 0.09090909090909091 - nodes in this community are weakly interconnected._