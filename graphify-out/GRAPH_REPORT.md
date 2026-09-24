# Graph Report - pageant_tabulator  (2026-09-24)

## Corpus Check
- 385 files · ~2,160,308 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1131 nodes · 2003 edges · 308 communities (29 shown, 62 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 15 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `42c36122`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Ms_candidate
- Ms_prelim_score
- composer.json
- JudgeAppController
- Mr_candidate
- package.json
- sweet-alert.js
- Illuminate\Support\Facades\Auth
- Illuminate\Database\Eloquent\Relations\HasMany
- TestCase
- Ms_prepageant_score
- User
- Illuminate\Database\Eloquent\Factories\HasFactory
- Mr_prelim_score
- Controller
- Illuminate\Http\Request
- Ms_deptuni_score
- Mr_ravewear_score
- Illuminate\Database\Seeder
- CLAUDE.md
- Livewire\Component
- Mr_swimwear_score
- README.md
- Mr_formalwear_score
- Mr_natlcost_score
- Mr_qna_score
- Ms_ravewear_score
- AppServiceProvider
- Illuminate\Database\Schema\Blueprint
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Console\Seeds\WithoutModelEvents
- CandidateController.php
- VerificationController.php
- Mr_final_score
- Ms_final_score
- ExampleTest
- UserFactory
- RouteServiceProvider
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
- judge_app/layouts/app.blade.php
- create.blade.php
- edit.blade.php
- views/layouts/app.blade.php

## God Nodes (most connected - your core abstractions)
1. `Ms_candidate` - 113 edges
2. `Mr_candidate` - 108 edges
3. `Ms_ranking` - 83 edges
4. `Mr_ranking` - 77 edges
5. `User` - 64 edges
6. `MsUepPrelimReportsController` - 47 edges
7. `Mr_final_rank` - 42 edges
8. `Ms_final_rank` - 42 edges
9. `MrUepPrelimReportsController` - 41 edges
10. `MrUepPrePageantReportsController` - 29 edges

## Surprising Connections (you probably didn't know these)
- `ConfirmPasswordController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/ConfirmPasswordController.php → app/Http/Controllers/Controller.php
- `LoginController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/LoginController.php → app/Http/Controllers/Controller.php
- `RegisterController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/RegisterController.php → app/Http/Controllers/Controller.php
- `VerificationController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/VerificationController.php → app/Http/Controllers/Controller.php
- `CandidateController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/CandidateController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (308 total, 62 thin omitted)

### Community 0 - "Ms_candidate"
Cohesion: 0.07
Nodes (8): MsUepFinalReportsController, MsUepPrelimReportsController, MsUepPrePageantReportsController, Ms_candidate, Ms_final_rank, Ms_ranking, MsRankingSeeder, PDF

### Community 1 - "Ms_prelim_score"
Cohesion: 0.06
Nodes (11): FormalwearScoreBoardComponent, NationalcostumeScoreBoardComponent, QnaScoreBoardComponent, SwimwearScoreBoardComponent, Ms_formalwear_score, Ms_natlcost_score, Ms_prelim_score, Ms_qna_score (+3 more)

### Community 2 - "composer.json"
Cohesion: 0.04
Nodes (46): pestphp/pest-plugin, autoload, autoload-dev, psr-4, files, psr-4, config, allow-plugins (+38 more)

### Community 4 - "Mr_candidate"
Cohesion: 0.06
Nodes (12): MrUepFinalReportsController, MrUepPrelimReportsController, MrUepPrePageantReportsController, Mr_candidate, Mr_final_rank, Mr_ranking, MrFinalRankingSeeder, MrRankingSeeder (+4 more)

### Community 5 - "package.json"
Cohesion: 0.11
Nodes (18): devDependencies, @fontsource-variable/ibm-plex-sans, @fortawesome/fontawesome-free, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, private (+10 more)

### Community 6 - "sweet-alert.js"
Cohesion: 0.53
Nodes (11): a(), c(), e(), f(), i(), l(), n(), o() (+3 more)

### Community 7 - "Illuminate\Support\Facades\Auth"
Cohesion: 0.16
Nodes (7): ScoreBoardComponent, TalentScoreBoardComponent, Mr_prepageant_score, Mr_talent_score, Illuminate\Support\Facades\Auth, Illuminate\Support\Facades\Redirect, Illuminate\Support\Facades\Session

### Community 9 - "TestCase"
Cohesion: 0.22
Nodes (5): Illuminate\Contracts\Console\Kernel, Illuminate\Foundation\Testing\TestCase, CreatesApplication, ExampleTest, TestCase

### Community 10 - "Ms_prepageant_score"
Cohesion: 0.22
Nodes (4): ScoreBoardComponent, TalentScoreBoardComponent, Ms_prepageant_score, Ms_talent_score

### Community 11 - "User"
Cohesion: 0.15
Nodes (7): UserController, User, MrFormalWearScoreSeeder, MsFormalWearScoreSeeder, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Laravel\Sanctum\HasApiTokens

### Community 12 - "Illuminate\Database\Eloquent\Factories\HasFactory"
Cohesion: 0.17
Nodes (3): Stage, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model

### Community 13 - "Mr_prelim_score"
Cohesion: 0.21
Nodes (4): DepartmentaluniformScoreBoardComponent, ScoreBoardComponent, Mr_deptuni_score, Mr_prelim_score

### Community 14 - "Controller"
Cohesion: 0.20
Nodes (10): ForgotPasswordController, ResetPasswordController, Controller, HomeController, Illuminate\Foundation\Auth\Access\AuthorizesRequests, Illuminate\Foundation\Auth\ResetsPasswords, Illuminate\Foundation\Auth\SendsPasswordResetEmails, Illuminate\Foundation\Bus\DispatchesJobs (+2 more)

### Community 15 - "Illuminate\Http\Request"
Cohesion: 0.20
Nodes (7): RedirectIfAuthenticated, TrustProxies, UserAccess, Closure, Illuminate\Contracts\Http\Kernel, Illuminate\Http\Middleware\TrustProxies, Illuminate\Http\Request

### Community 18 - "Illuminate\Database\Seeder"
Cohesion: 0.15
Nodes (7): DatabaseSeeder, MrTalentScoreSeeder, MsCandidateSeeder, MsFinalRankingSeeder, PrelimSeeder, StageSeeder, Illuminate\Database\Seeder

### Community 19 - "CLAUDE.md"
Cohesion: 0.29
Nodes (5): Architecture, Commands, Gotchas, UI, What this is

### Community 20 - "Livewire\Component"
Cohesion: 0.18
Nodes (4): ActiveStatusComponent, StageControllerComponent, ScoreBoardComponent, Livewire\Component

### Community 22 - "README.md"
Cohesion: 0.33
Nodes (5): 3 Judges (sample) you can set new, Admin, For candidates image, please follow the size and format been used in this app, and rename it according to candidate number (eg. 1.jpg)., migrate tables and initialize everything, to be able to connect IPAD/Tablet in one network, make sure you already set up your local ip first

### Community 27 - "AppServiceProvider"
Cohesion: 0.24
Nodes (4): AppServiceProvider, BroadcastServiceProvider, Illuminate\Support\Facades\Broadcast, Illuminate\Support\ServiceProvider

### Community 30 - "Illuminate\Database\Console\Seeds\WithoutModelEvents"
Cohesion: 0.15
Nodes (5): MrCandidateSeeder, MrQnaScoreSeeder, MsRavewearScoreSeeder, MsSwimWearScoreSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents

### Community 36 - "UserFactory"
Cohesion: 0.25
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str

### Community 37 - "RouteServiceProvider"
Cohesion: 0.32
Nodes (4): ConfirmPasswordController, RouteServiceProvider, Illuminate\Foundation\Auth\ConfirmsPasswords, Illuminate\Foundation\Support\Providers\RouteServiceProvider

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

## Knowledge Gaps
- **80 isolated node(s):** `name`, `type`, `description`, `keywords`, `license` (+75 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 509 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **62 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Ms_candidate` connect `Ms_candidate` to `Ms_prelim_score`, `MsFinalScoreSeeder.php`, `Mr_candidate`, `MsNatlCostScoreSeeder.php`, `MsPrepageantScoreSeeder.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `User`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `Controller`, `MsDeptUniScoreSeeder.php`, `MsTalentScoreSeeder.php`, `Illuminate\Database\Seeder`, `Illuminate\Database\Console\Seeds\WithoutModelEvents`, `CandidateController.php`?**
  _High betweenness centrality (0.061) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `Ms_candidate`, `Ms_prelim_score`, `Mr_candidate`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `Controller`, `Illuminate\Database\Seeder`, `Illuminate\Database\Console\Seeds\WithoutModelEvents`, `AdminUserSeeder.php`, `RegisterController.php`, `MrRavewearScoreSeeder.php`, `MsDeptUniScoreSeeder.php`, `MsTalentScoreSeeder.php`, `MrDeptUniScoreSeeder.php`, `MrFinalScoreSeeder.php`, `MrNatlCostScoreSeeder.php`, `MrPrelimScoreSeeder.php`, `MrPrepageantScoreSeeder.php`, `MrSwimWearScoreSeeder.php`, `MsFinalScoreSeeder.php`, `MsNatlCostScoreSeeder.php`, `MsPrepageantScoreSeeder.php`?**
  _High betweenness centrality (0.043) - this node is a cross-community bridge._
- **Why does `Controller` connect `Controller` to `VerificationController.php`, `Ms_candidate`, `JudgeAppController`, `Mr_candidate`, `RouteServiceProvider`, `RegisterController.php`, `User`, `LoginController.php`, `CandidateController.php`?**
  _High betweenness centrality (0.042) - this node is a cross-community bridge._
- **What connects `name`, `type`, `description` to the rest of the system?**
  _80 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Ms_candidate` be split into smaller, more focused modules?**
  _Cohesion score 0.06954930221917181 - nodes in this community are weakly interconnected._
- **Should `Ms_prelim_score` be split into smaller, more focused modules?**
  _Cohesion score 0.05952380952380952 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.0425531914893617 - nodes in this community are weakly interconnected._