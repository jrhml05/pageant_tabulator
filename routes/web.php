<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Livewire\Admin\CategoryComponent;


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/logout', function() {
    Session::flush();
    Auth::logout();
    return redirect('/login');
});

// ADMIN
    Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('barangays', App\Http\Controllers\BarangayController::class);
    Route::resource('candidates', App\Http\Controllers\CandidateController::class);
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::get('categories/{category}/subcategory', [App\Http\Controllers\CategoryController::class, 'getsubcategory'])->name('subcategory');
    Route::resource('judges', App\Http\Controllers\UserController::class);
    Route::get('/settings', function() {
        return view('admin.settings.index');
    })->name('settings');

    //RESULTS
        Route::get('/prepageant', [App\Http\Controllers\ReportsController::class, 'prepageant'])->name('prepageant');
        Route::get('/talent', [App\Http\Controllers\ReportsController::class, 'talent'])->name('talent');
        Route::get('/top5talent', [App\Http\Controllers\ReportsController::class, 'top5talent'])->name('top5talent');
        Route::get('/showrank', [App\Http\Controllers\ReportsController::class, 'showrank'])->name('showrank');
        Route::get('/preliminaries', [App\Http\Controllers\ReportsController::class, 'preliminaries'])->name('preliminaries');
        Route::get('/swimsuit', [App\Http\Controllers\ReportsController::class, 'swimsuit'])->name('swimsuit');
        Route::get('/eveninggown', [App\Http\Controllers\ReportsController::class, 'eveninggown'])->name('eveninggown');
        Route::get('/top12', [App\Http\Controllers\ReportsController::class, 'top12'])->name('top12');
        Route::get('/semifinal', [App\Http\Controllers\ReportsController::class, 'semifinal'])->name('semifinal');
        Route::get('/top5', [App\Http\Controllers\ReportsController::class, 'top5'])->name('top5');
        Route::get('/final', [App\Http\Controllers\ReportsController::class, 'final'])->name('final');
        Route::get('/finalresult', [App\Http\Controllers\ReportsController::class, 'finalresult'])->name('finalresult');



    //INDIVIDUAL RESULTS
        Route::get('/prepageant_judge1', [App\Http\Controllers\ReportsController::class, 'prepageantjudge1'])->name('prepageant_judge1');
        Route::get('/prepageant_judge2', [App\Http\Controllers\ReportsController::class, 'prepageantjudge2'])->name('prepageant_judge2');
        Route::get('/prepageant_judge3', [App\Http\Controllers\ReportsController::class, 'prepageantjudge3'])->name('prepageant_judge3');

        Route::get('/talent_judge1', [App\Http\Controllers\ReportsController::class, 'talentjudge1'])->name('talent_judge1');
        Route::get('/talent_judge2', [App\Http\Controllers\ReportsController::class, 'talentjudge2'])->name('talent_judge2');
        Route::get('/talent_judge3', [App\Http\Controllers\ReportsController::class, 'talentjudge3'])->name('talent_judge3');

        Route::get('/prelim_judge1', [App\Http\Controllers\ReportsController::class, 'prelimjudge1'])->name('prelim_judge1');
        Route::get('/prelim_judge2', [App\Http\Controllers\ReportsController::class, 'prelimjudge2'])->name('prelim_judge2');
        Route::get('/prelim_judge3', [App\Http\Controllers\ReportsController::class, 'prelimjudge3'])->name('prelim_judge3');
        Route::get('/prelim_judge4', [App\Http\Controllers\ReportsController::class, 'prelimjudge4'])->name('prelim_judge4');
        Route::get('/prelim_judge5', [App\Http\Controllers\ReportsController::class, 'prelimjudge5'])->name('prelim_judge5');

        Route::get('/semi_judge1', [App\Http\Controllers\ReportsController::class, 'semijudge1'])->name('semi_judge1');
        Route::get('/semi_judge2', [App\Http\Controllers\ReportsController::class, 'semijudge2'])->name('semi_judge2');
        Route::get('/semi_judge3', [App\Http\Controllers\ReportsController::class, 'semijudge3'])->name('semi_judge3');
        Route::get('/semi_judge4', [App\Http\Controllers\ReportsController::class, 'semijudge4'])->name('semi_judge4');
        Route::get('/semi_judge5', [App\Http\Controllers\ReportsController::class, 'semijudge5'])->name('semi_judge5');

        Route::get('/final_judge1', [App\Http\Controllers\ReportsController::class, 'finaljudge1'])->name('final_judge1');
        Route::get('/final_judge2', [App\Http\Controllers\ReportsController::class, 'finaljudge2'])->name('final_judge2');
        Route::get('/final_judge3', [App\Http\Controllers\ReportsController::class, 'finaljudge3'])->name('final_judge3');
        Route::get('/final_judge4', [App\Http\Controllers\ReportsController::class, 'finaljudge4'])->name('final_judge4');
        Route::get('/final_judge5', [App\Http\Controllers\ReportsController::class, 'finaljudge5'])->name('final_judge5');

    //PDF

        Route::get('/pdfprepageant', [App\Http\Controllers\ReportsController::class, 'pdfprepageant'])->name('pdfprepageant');
        Route::get('/pdfprepageant_judge1', [App\Http\Controllers\ReportsController::class, 'pdfprepageantjudge1'])->name('pdfprepageant_judge1');
        Route::get('/pdfprepageant_judge2', [App\Http\Controllers\ReportsController::class, 'pdfprepageantjudge2'])->name('pdfprepageant_judge2');
        Route::get('/pdfprepageant_judge3', [App\Http\Controllers\ReportsController::class, 'pdfprepageantjudge3'])->name('pdfprepageant_judge3');

        Route::get('/pdftalent', [App\Http\Controllers\ReportsController::class, 'pdftalent'])->name('pdftalent');
        Route::get('/pdftalent_judge1', [App\Http\Controllers\ReportsController::class, 'pdftalentjudge1'])->name('pdftalent_judge1');
        Route::get('/pdftalent_judge2', [App\Http\Controllers\ReportsController::class, 'pdftalentjudge2'])->name('pdftalent_judge2');
        Route::get('/pdftalent_judge3', [App\Http\Controllers\ReportsController::class, 'pdftalentjudge3'])->name('pdftalent_judge3');

        Route::get('/pdfprelim', [App\Http\Controllers\ReportsController::class, 'pdfprelim'])->name('pdfprelim');
        Route::get('/pdfprelim_judge1', [App\Http\Controllers\ReportsController::class, 'pdfprelimjudge1'])->name('pdfprelim_judge1');
        Route::get('/pdfprelim_judge2', [App\Http\Controllers\ReportsController::class, 'pdfprelimjudge2'])->name('pdfprelim_judge2');
        Route::get('/pdfprelim_judge3', [App\Http\Controllers\ReportsController::class, 'pdfprelimjudge3'])->name('pdfprelim_judge3');
        Route::get('/pdfprelim_judge4', [App\Http\Controllers\ReportsController::class, 'pdfprelimjudge4'])->name('pdfprelim_judge4');
        Route::get('/pdfprelim_judge5', [App\Http\Controllers\ReportsController::class, 'pdfprelimjudge5'])->name('pdfprelim_judge5');

        Route::get('/pdfswimsuit', [App\Http\Controllers\ReportsController::class, 'pdfswimsuit'])->name('pdfswimsuit');
        Route::get('/pdfgown', [App\Http\Controllers\ReportsController::class, 'pdfgown'])->name('pdfgown');

        Route::get('/pdftop12', [App\Http\Controllers\ReportsController::class, 'pdftop12'])->name('pdftop12');

        Route::get('/pdfsemifinal', [App\Http\Controllers\ReportsController::class, 'pdfsemifinal'])->name('pdfsemifinal');
        Route::get('/pdfsemi_judge1', [App\Http\Controllers\ReportsController::class, 'pdfsemijudge1'])->name('pdfsemi_judge1');
        Route::get('/pdfsemi_judge2', [App\Http\Controllers\ReportsController::class, 'pdfsemijudge2'])->name('pdfsemi_judge2');
        Route::get('/pdfsemi_judge3', [App\Http\Controllers\ReportsController::class, 'pdfsemijudge3'])->name('pdfsemi_judge3');
        Route::get('/pdfsemi_judge4', [App\Http\Controllers\ReportsController::class, 'pdfsemijudge4'])->name('pdfsemi_judge4');
        Route::get('/pdfsemi_judge5', [App\Http\Controllers\ReportsController::class, 'pdfsemijudge5'])->name('pdfsemi_judge5');

        Route::get('/pdftop5', [App\Http\Controllers\ReportsController::class, 'pdftop5'])->name('pdftop5');

        Route::get('/pdffinal', [App\Http\Controllers\ReportsController::class, 'pdffinal'])->name('pdffinal');
        Route::get('/pdffinal_judge1', [App\Http\Controllers\ReportsController::class, 'pdffinaljudge1'])->name('pdffinal_judge1');
        Route::get('/pdffinal_judge2', [App\Http\Controllers\ReportsController::class, 'pdffinaljudge2'])->name('pdffinal_judge2');
        Route::get('/pdffinal_judge3', [App\Http\Controllers\ReportsController::class, 'pdffinaljudge3'])->name('pdffinal_judge3');
        Route::get('/pdffinal_judge4', [App\Http\Controllers\ReportsController::class, 'pdffinaljudge4'])->name('pdffinal_judge4');
        Route::get('/pdffinal_judge5', [App\Http\Controllers\ReportsController::class, 'pdffinaljudge5'])->name('pdffinal_judge5');

        Route::get('/pdffinal_result', [App\Http\Controllers\ReportsController::class, 'pdffinalresult'])->name('pdffinal_result');

    //RANKING
        Route::get('/prepageantrank', [App\Http\Controllers\ReportsController::class, 'prepageantrank'])->name('prepageantrank');
        Route::get('/talentrank', [App\Http\Controllers\ReportsController::class, 'talentrank'])->name('talentrank');
        Route::get('/prelimrank', [App\Http\Controllers\ReportsController::class, 'prelimrank'])->name('prelimrank');
        Route::get('/swimsuitrank', [App\Http\Controllers\ReportsController::class, 'swimsuitrank'])->name('swimsuitrank');
        Route::get('/eveninggownrank', [App\Http\Controllers\ReportsController::class, 'eveninggownrank'])->name('eveninggownrank');
        Route::get('/semifinalrank', [App\Http\Controllers\ReportsController::class, 'semifinalrank'])->name('semifinalrank');
        Route::get('/finalrank', [App\Http\Controllers\ReportsController::class, 'finalrank'])->name('finalrank');

    //EXTRA
        Route::get('/putscoresprelim', [App\Http\Controllers\ReportsController::class, 'putscoresprelim'])->name('putscoresprelim');
        Route::get('/putsemiscore', [App\Http\Controllers\ReportsController::class, 'putsemiscore'])->name('putsemiscore');
        Route::get('/putfinalscore', [App\Http\Controllers\ReportsController::class, 'putfinalscore'])->name('putfinalscore');

});

// JUDGE
Route::middleware(['auth', 'user-access:judge'])->group(function () {
    Route::get('/judge-app', [App\Http\Controllers\JudgeAppController::class, 'index'])->name('judge.app');
    Route::get('/judge-app/{stage}/score-board', [App\Http\Controllers\JudgeAppController::class, 'scoreBoard'])->name('judge.app.score');
    Route::get('/judge-app/{stage}/talent-score-board', [App\Http\Controllers\JudgeAppController::class, 'talentScoreBoard'])->name('judge.app.talent.score');
    Route::get('/judge-app/final-score-board', [App\Http\Controllers\JudgeAppController::class, 'finalScoreBoard'])->name('judge.app.final.score');

});

// EXTRA
Route::get('/back', function() {
    return Redirect::back();
})->name('back');


