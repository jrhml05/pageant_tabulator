# Graph Report - pageant_tabulator  (2026-09-24)

## Corpus Check
- Large corpus: 2134 files · ~3,536,897 words. Semantic extraction will be expensive (many Claude tokens). Consider running on a subfolder.

## Summary
- 1239 nodes · 2173 edges · 325 communities (27 shown, 79 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 5 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Ms Report Controllers
- Ms Prelim Scoreboards
- Legacy ReportsController
- Judge App Routing
- Mr Prelim Category Reports
- Mr Pre-Pageant Reports
- Legacy PDF Reports
- Mr Pre-Pageant Scoreboards
- Candidate Score Relations
- Legacy Barangay Components
- Ms Talent Scoreboard
- Judge Account CRUD
- Eloquent Score Models
- Mr Dept Uniform Scoreboard
- Password Reset Auth
- Role Access Middleware
- Stage Management
- Ms Pre-Pageant Scoreboard
- Database Seeding Entry
- Mr Prelim Ranking
- Admin Livewire Toggles
- Mr Swimwear Scoreboard
- Legacy Category CRUD
- Mr Formalwear Scoreboard
- Mr National Costume Scoreboard
- Mr QnA Scoreboard
- Ms Ravewear Scoreboard
- App Service Providers
- Users And Score Migrations
- Password Reset Migrations
- Candidate Score Seeders
- Candidate CRUD
- Mr Final Reports
- Mr Final Scoreboard
- Ms Final Scoreboard
- Legacy Semifinal Scoreboard
- Config And User Factory
- Password Confirm Routing
- Admin And Judge Seeders
- Event Service Provider
- Routes web
- Migration create_permission_tables
- RegisterController
- Kernel
- Handler
- LoginController
- MrUepPrePageantReportsController
- ReportsController
- View livewire/admin/category-component
- HomeController
- Authenticate
- TrustHosts
- AuthServiceProvider
- Config logging
- View livewire/admin/sub-category-component
- View livewire/judge/final-score-board-component
- Kernel (56)
- EncryptCookies
- PreventRequestsDuringMaintenance
- TrimStrings
- ValidateSignature
- VerifyCsrfToken
- Seeder MrDeptUniScoreSeeder
- Seeder MrFinalScoreSeeder
- Seeder MrFormalWearScoreSeeder
- Seeder MrNatlCostScoreSeeder
- Seeder MrPrelimScoreSeeder
- Seeder MrPrepageantScoreSeeder
- Seeder MrQnaScoreSeeder
- Seeder MrRankingSeeder
- Seeder MrSwimWearScoreSeeder
- Seeder MrTalentScoreSeeder
- Seeder MsFinalScoreSeeder
- Seeder MsFormalWearScoreSeeder
- Seeder MsNatlCostScoreSeeder
- Seeder MsPrepageantScoreSeeder
- Seeder MsQnaScoreSeeder
- Seeder MsRavewearScoreSeeder
- View livewire/admin/candidates-component
- View final/mr/score-board-component
- View final/ms/score-board-component
- View preliminaries/mr/departmentaluniform-score-board-component
- View preliminaries/mr/formalwear-score-board-component
- View preliminaries/mr/nationalcostume-score-board-component
- View preliminaries/mr/qna-score-board-component
- View preliminaries/mr/score-board-component
- View preliminaries/mr/swimwear-score-board-component
- View preliminaries/ms/departmentaluniform-score-board-component
- View preliminaries/ms/formalwear-score-board-component
- View preliminaries/ms/nationalcostume-score-board-component
- View preliminaries/ms/qna-score-board-component
- View preliminaries/ms/score-board-component
- View preliminaries/ms/swimwear-score-board-component
- View prepageant/mr/ravewear-score-board-component
- View prepageant/mr/score-board-component
- View prepageant/mr/talent-score-board-component
- View prepageant/ms/ravewear-score-board-component
- View prepageant/ms/score-board-component
- View prepageant/ms/talent-score-board-component
- View livewire/judge/score-board-component
- View judge/semifinal/score-board-component
- View livewire/judge/talent-score-board-component
- Config app
- Config sanctum
- View layouts/master
- View judge_app/layouts/app

## God Nodes (most connected - your core abstractions)
1. `Ms_candidate` - 118 edges
2. `Mr_candidate` - 104 edges
3. `Ms_ranking` - 90 edges
4. `Mr_ranking` - 77 edges
5. `ReportsController` - 67 edges
6. `User` - 64 edges
7. `MsUepPrelimReportsController` - 47 edges
8. `Mr_final_rank` - 42 edges
9. `Ms_final_rank` - 42 edges
10. `MrUepPrelimReportsController` - 41 edges

## Surprising Connections (you probably didn't know these)
- `ConfirmPasswordController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/ConfirmPasswordController.php → app/Http/Controllers/Controller.php
- `LoginController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/LoginController.php → app/Http/Controllers/Controller.php
- `RegisterController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/RegisterController.php → app/Http/Controllers/Controller.php
- `CandidateController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/CandidateController.php → app/Http/Controllers/Controller.php
- `CategoryController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/CategoryController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (325 total, 79 thin omitted)

### Community 0 - "Ms Report Controllers"
Cohesion: 0.06
Nodes (10): MsUepFinalReportsController, MsUepPrelimReportsController, MsUepPrePageantReportsController, Ms_candidate, Ms_final_rank, Ms_ranking, MsFinalRankingSeeder, MsRankingSeeder (+2 more)

### Community 1 - "Ms Prelim Scoreboards"
Cohesion: 0.06
Nodes (12): DepartmentaluniformScoreBoardComponent, FormalwearScoreBoardComponent, NationalcostumeScoreBoardComponent, QnaScoreBoardComponent, ScoreBoardComponent, SwimwearScoreBoardComponent, Ms_deptuni_score, Ms_formalwear_score (+4 more)

### Community 7 - "Mr Pre-Pageant Scoreboards"
Cohesion: 0.11
Nodes (6): RavewearScoreBoardComponent, ScoreBoardComponent, TalentScoreBoardComponent, Mr_prepageant_score, Mr_ravewear_score, Mr_talent_score

### Community 9 - "Legacy Barangay Components"
Cohesion: 0.12
Nodes (7): FinalScoreGenerator, CandidatesComponent, FinalScoreBoardComponent, App\Models\Barangay, App\Models\FinalScore, Candidate, Illuminate\Console\Command

### Community 10 - "Ms Talent Scoreboard"
Cohesion: 0.16
Nodes (3): TalentScoreBoardComponent, TalentScoreBoardComponent, Ms_talent_score

### Community 11 - "Judge Account CRUD"
Cohesion: 0.15
Nodes (7): UserController, User, MsDeptUniScoreSeeder, MsPrelimScoreSeeder, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Laravel\Sanctum\HasApiTokens

### Community 13 - "Mr Dept Uniform Scoreboard"
Cohesion: 0.16
Nodes (4): DepartmentaluniformScoreBoardComponent, ScoreBoardComponent, Mr_deptuni_score, Mr_prelim_score

### Community 14 - "Password Reset Auth"
Cohesion: 0.20
Nodes (11): ForgotPasswordController, ResetPasswordController, VerificationController, Controller, Illuminate\Foundation\Auth\Access\AuthorizesRequests, Illuminate\Foundation\Auth\ResetsPasswords, Illuminate\Foundation\Auth\SendsPasswordResetEmails, Illuminate\Foundation\Auth\VerifiesEmails (+3 more)

### Community 15 - "Role Access Middleware"
Cohesion: 0.19
Nodes (7): IsAdmin, RedirectIfAuthenticated, TrustProxies, UserAccess, Closure, Illuminate\Http\Middleware\TrustProxies, Illuminate\Http\Request

### Community 16 - "Stage Management"
Cohesion: 0.17
Nodes (3): StageController, StageControllerComponent, Stage

### Community 17 - "Ms Pre-Pageant Scoreboard"
Cohesion: 0.16
Nodes (3): ScoreBoardComponent, ScoreBoardComponent, Ms_prepageant_score

### Community 18 - "Database Seeding Entry"
Cohesion: 0.17
Nodes (6): DatabaseSeeder, MsCandidateSeeder, MsTalentScoreSeeder, PrelimSeeder, StageSeeder, Illuminate\Database\Seeder

### Community 20 - "Admin Livewire Toggles"
Cohesion: 0.16
Nodes (4): ActiveStatusComponent, SubCategoryComponent, App\Models\SubCategory, Livewire\Component

### Community 27 - "App Service Providers"
Cohesion: 0.24
Nodes (4): AppServiceProvider, BroadcastServiceProvider, Illuminate\Support\Facades\Broadcast, Illuminate\Support\ServiceProvider

### Community 30 - "Candidate Score Seeders"
Cohesion: 0.20
Nodes (4): MrCandidateSeeder, MrRavewearScoreSeeder, MsSwimWearScoreSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents

### Community 35 - "Legacy Semifinal Scoreboard"
Cohesion: 0.22
Nodes (3): ScoreBoardComponent, App\Models\SemifinalScore, Illuminate\Support\Facades\Auth

### Community 36 - "Config And User Factory"
Cohesion: 0.25
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str

### Community 37 - "Password Confirm Routing"
Cohesion: 0.32
Nodes (4): ConfirmPasswordController, RouteServiceProvider, Illuminate\Foundation\Auth\ConfirmsPasswords, Illuminate\Foundation\Support\Providers\RouteServiceProvider

### Community 38 - "Admin And Judge Seeders"
Cohesion: 0.25
Nodes (3): AdminUserSeeder, JudgeSeeder, Illuminate\Support\Facades\Hash

### Community 39 - "Event Service Provider"
Cohesion: 0.25
Nodes (5): EventServiceProvider, Illuminate\Auth\Events\Registered, Illuminate\Auth\Listeners\SendEmailVerificationNotification, Illuminate\Foundation\Support\Providers\EventServiceProvider, Illuminate\Support\Facades\Event

### Community 40 - "Routes web"
Cohesion: 0.25
Nodes (5): Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\Facades\Redirect, Illuminate\Support\Facades\Route, Illuminate\Support\Facades\Session

### Community 42 - "RegisterController"
Cohesion: 0.33
Nodes (3): RegisterController, Illuminate\Foundation\Auth\RegistersUsers, Illuminate\Support\Facades\Validator

### Community 43 - "Kernel"
Cohesion: 0.40
Nodes (3): Kernel, Illuminate\Console\Scheduling\Schedule, Illuminate\Foundation\Console\Kernel

### Community 44 - "Handler"
Cohesion: 0.40
Nodes (3): Handler, Illuminate\Foundation\Exceptions\Handler, Throwable

### Community 47 - "ReportsController"
Cohesion: 0.40
Nodes (4): App\Models\FinalRanking, App\Models\PrelimScore, App\Models\Score, App\Models\SubScore

### Community 48 - "View livewire/admin/category-component"
Cohesion: 0.40
Nodes (3): filter, addRow, remove({{ $index }})

### Community 53 - "Config logging"
Cohesion: 0.50
Nodes (3): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler

## Knowledge Gaps
- **56 isolated node(s):** `try`, `layouts.navigation`, `edit({{ $barangay->id }})`, `filter`, `addRow` (+51 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 537 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **79 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Ms_candidate` connect `Ms Report Controllers` to `Seeder MsFinalScoreSeeder`, `Seeder MsFormalWearScoreSeeder`, `Seeder MsNatlCostScoreSeeder`, `Seeder MsPrepageantScoreSeeder`, `Legacy PDF Reports`, `Seeder MsQnaScoreSeeder`, `Candidate Score Relations`, `Seeder MsRavewearScoreSeeder`, `Judge Account CRUD`, `Eloquent Score Models`, `ReportsController`, `HomeController`, `Database Seeding Entry`, `Candidate Score Seeders`?**
  _High betweenness centrality (0.065) - this node is a cross-community bridge._
- **Why does `Controller` connect `Password Reset Auth` to `Mr Final Reports`, `Ms Report Controllers`, `Legacy ReportsController`, `Judge App Routing`, `Mr Prelim Category Reports`, `Password Confirm Routing`, `Mr Pre-Pageant Reports`, `RegisterController`, `Judge Account CRUD`, `LoginController`, `Stage Management`, `HomeController`, `Legacy Category CRUD`, `Candidate CRUD`?**
  _High betweenness centrality (0.057) - this node is a cross-community bridge._
- **Why does `Mr_candidate` connect `Mr Prelim Category Reports` to `Mr Final Reports`, `Seeder MrSwimWearScoreSeeder`, `Seeder MrTalentScoreSeeder`, `Mr Pre-Pageant Reports`, `Candidate Score Relations`, `Eloquent Score Models`, `MrUepPrePageantReportsController`, `Mr Prelim Ranking`, `Seeder MrQnaScoreSeeder`, `Seeder MrDeptUniScoreSeeder`, `Seeder MrFinalScoreSeeder`, `Seeder MrFormalWearScoreSeeder`, `Seeder MrNatlCostScoreSeeder`, `Seeder MrPrelimScoreSeeder`, `Seeder MrPrepageantScoreSeeder`, `Candidate Score Seeders`, `Seeder MrRankingSeeder`?**
  _High betweenness centrality (0.038) - this node is a cross-community bridge._
- **What connects `try`, `layouts.navigation`, `edit({{ $barangay->id }})` to the rest of the system?**
  _56 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Ms Report Controllers` be split into smaller, more focused modules?**
  _Cohesion score 0.05702970297029703 - nodes in this community are weakly interconnected._
- **Should `Ms Prelim Scoreboards` be split into smaller, more focused modules?**
  _Cohesion score 0.058069381598793365 - nodes in this community are weakly interconnected._
- **Should `Legacy ReportsController` be split into smaller, more focused modules?**
  _Cohesion score 0.05555555555555555 - nodes in this community are weakly interconnected._