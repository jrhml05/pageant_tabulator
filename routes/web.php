<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::get('/logout', function () {
    Session::flush();
    Auth::logout();
    return redirect('/login');
});

// ADMIN
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/candidates', [App\Http\Controllers\CandidateController::class, 'index'])->name('candidates.index');
    Route::resource('judges', App\Http\Controllers\UserController::class);
    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('settings');

    //RESULTS for Ms. LCUAA PREPAGEANT
    Route::get('/ms_prepageant', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_prepageant'])->name('ms_prepageant');
    Route::get('/ms_rave_wear', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_rave_wear'])->name('ms_rave_wear');
    Route::get('/ms_talent', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_talent'])->name('ms_talent');

    //INDIVIDUAL RESULTS
    Route::get('/ms_prepageant_judge1', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_prepageantjudge1'])->name('ms_prepageant_judge1');
    Route::get('/ms_prepageant_judge2', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_prepageantjudge2'])->name('ms_prepageant_judge2');
    Route::get('/ms_prepageant_judge3', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_prepageantjudge3'])->name('ms_prepageant_judge3');

    Route::get('/ms_rave_wear_judge1', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_rave_wearjudge1'])->name('ms_rave_wear_judge1');
    Route::get('/ms_rave_wear_judge2', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_rave_wearjudge2'])->name('ms_rave_wear_judge2');
    Route::get('/ms_rave_wear_judge3', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_rave_wearjudge3'])->name('ms_rave_wear_judge3');

    Route::get('/ms_talent_judge1', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_talentjudge1'])->name('ms_talent_judge1');
    Route::get('/ms_talent_judge2', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_talentjudge2'])->name('ms_talent_judge2');
    Route::get('/ms_talent_judge3', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_talentjudge3'])->name('ms_talent_judge3');

    //PDF Result
    Route::get('/ms_pdfprepageant', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdfprepageant'])->name('ms_pdfprepageant');
    Route::get('/ms_pdfprepageant_judge1', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdfprepageantjudge1'])->name('ms_pdfprepageant_judge1');
    Route::get('/ms_pdfprepageant_judge2', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdfprepageantjudge2'])->name('ms_pdfprepageant_judge2');
    Route::get('/ms_pdfprepageant_judge3', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdfprepageantjudge3'])->name('ms_pdfprepageant_judge3');

    Route::get('/ms_pdfrave_wear', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdfrave_wear'])->name('ms_pdfrave_wear');
    Route::get('/ms_pdfrave_wear_judge1', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdfrave_wearjudge1'])->name('ms_pdfrave_wear_judge1');
    Route::get('/ms_pdfrave_wear_judge2', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdfrave_wearjudge2'])->name('ms_pdfrave_wear_judge2');
    Route::get('/ms_pdfrave_wear_judge3', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdfrave_wearjudge3'])->name('ms_pdfrave_wear_judge3');

    Route::get('/ms_pdftalent', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdftalent'])->name('ms_pdftalent');
    Route::get('/ms_pdftalent_judge1', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdftalentjudge1'])->name('ms_pdftalent_judge1');
    Route::get('/ms_pdftalent_judge2', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdftalentjudge2'])->name('ms_pdftalent_judge2');
    Route::get('/ms_pdftalent_judge3', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_pdftalentjudge3'])->name('ms_pdftalent_judge3');

    //RESULTS for Mr. LCUAA PREPAGEANT
    Route::get('/mr_prepageant', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_prepageant'])->name('mr_prepageant');
    Route::get('/mr_rave_wear', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_rave_wear'])->name('mr_rave_wear');
    Route::get('/mr_talent', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_talent'])->name('mr_talent');

    //INDIVIDUAL RESULTS
    Route::get('/mr_prepageant_judge1', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_prepageantjudge1'])->name('mr_prepageant_judge1');
    Route::get('/mr_prepageant_judge2', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_prepageantjudge2'])->name('mr_prepageant_judge2');
    Route::get('/mr_prepageant_judge3', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_prepageantjudge3'])->name('mr_prepageant_judge3');

    Route::get('/mr_rave_wear_judge1', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_rave_wearjudge1'])->name('mr_rave_wear_judge1');
    Route::get('/mr_rave_wear_judge2', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_rave_wearjudge2'])->name('mr_rave_wear_judge2');
    Route::get('/mr_rave_wear_judge3', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_rave_wearjudge3'])->name('mr_rave_wear_judge3');

    Route::get('/mr_talent_judge1', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_talentjudge1'])->name('mr_talent_judge1');
    Route::get('/mr_talent_judge2', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_talentjudge2'])->name('mr_talent_judge2');
    Route::get('/mr_talent_judge3', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_talentjudge3'])->name('mr_talent_judge3');

    //PDF Result
    Route::get('/mr_pdfprepageant', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdfprepageant'])->name('mr_pdfprepageant');
    Route::get('/mr_pdfprepageant_judge1', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdfprepageantjudge1'])->name('mr_pdfprepageant_judge1');
    Route::get('/mr_pdfprepageant_judge2', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdfprepageantjudge2'])->name('mr_pdfprepageant_judge2');
    Route::get('/mr_pdfprepageant_judge3', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdfprepageantjudge3'])->name('mr_pdfprepageant_judge3');

    Route::get('/mr_pdfrave_wear', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdfrave_wear'])->name('mr_pdfrave_wear');
    Route::get('/mr_pdfrave_wear_judge1', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdfrave_wearjudge1'])->name('mr_pdfrave_wear_judge1');
    Route::get('/mr_pdfrave_wear_judge2', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdfrave_wearjudge2'])->name('mr_pdfrave_wear_judge2');
    Route::get('/mr_pdfrave_wear_judge3', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdfrave_wearjudge3'])->name('mr_pdfrave_wear_judge3');

    Route::get('/mr_pdftalent', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdftalent'])->name('mr_pdftalent');
    Route::get('/mr_pdftalent_judge1', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdftalentjudge1'])->name('mr_pdftalent_judge1');
    Route::get('/mr_pdftalent_judge2', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdftalentjudge2'])->name('mr_pdftalent_judge2');
    Route::get('/mr_pdftalent_judge3', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_pdftalentjudge3'])->name('mr_pdftalent_judge3');

    //RESULTS for Mr. LCUAA PRELIMINARIES
    Route::get('/mr_prelim', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_prelim'])->name('mr_prelim');
    Route::get('/mr_national_costume', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_national_costume'])->name('mr_national_costume');
    Route::get('/mr_departmental_uniform', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_departmental_uniform'])->name('mr_departmental_uniform');
    Route::get('/mr_swim_wear', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_swim_wear'])->name('mr_swim_wear');
    Route::get('/mr_formal_wear', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_formal_wear'])->name('mr_formal_wear');
    Route::get('/mr_qna', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_qna'])->name('mr_qna');
    Route::get('/mr_top_5', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_top_5'])->name('mr_top_5');

    //INDIVIDUAL RESULTS
    Route::get('/mr_prelim_judge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_prelimjudge1'])->name('mr_prelim_judge1');
    Route::get('/mr_prelim_judge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_prelimjudge2'])->name('mr_prelim_judge2');
    Route::get('/mr_prelim_judge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_prelimjudge3'])->name('mr_prelim_judge3');

    Route::get('/mr_national_costume_judge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_national_costumejudge1'])->name('mr_national_costume_judge1');
    Route::get('/mr_national_costume_judge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_national_costumejudge2'])->name('mr_national_costume_judge2');
    Route::get('/mr_national_costume_judge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_national_costumejudge3'])->name('mr_national_costume_judge3');

    Route::get('/mr_departmental_uniform_judge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_departmental_uniformjudge1'])->name('mr_departmental_uniform_judge1');
    Route::get('/mr_departmental_uniform_judge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_departmental_uniformjudge2'])->name('mr_departmental_uniform_judge2');
    Route::get('/mr_departmental_uniform_judge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_departmental_uniformjudge3'])->name('mr_departmental_uniform_judge3');

    Route::get('/mr_swim_wear_judge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_swim_wearjudge1'])->name('mr_swim_wear_judge1');
    Route::get('/mr_swim_wear_judge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_swim_wearjudge2'])->name('mr_swim_wear_judge2');
    Route::get('/mr_swim_wear_judge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_swim_wearjudge3'])->name('mr_swim_wear_judge3');

    Route::get('/mr_formal_wear_judge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_formal_wearjudge1'])->name('mr_formal_wear_judge1');
    Route::get('/mr_formal_wear_judge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_formal_wearjudge2'])->name('mr_formal_wear_judge2');
    Route::get('/mr_formal_wear_judge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_formal_wearjudge3'])->name('mr_formal_wear_judge3');

    Route::get('/mr_qna_judge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_qnajudge1'])->name('mr_qna_judge1');
    Route::get('/mr_qna_judge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_qnajudge2'])->name('mr_qna_judge2');
    Route::get('/mr_qna_judge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_qnajudge3'])->name('mr_qna_judge3');

    //PDF Result
    Route::get('/mr_pdfprelim', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfprelim'])->name('mr_pdfprelim');
    Route::get('/mr_pdfprelim_judge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfprelimjudge1'])->name('mr_pdfprelim_judge1');
    Route::get('/mr_pdfprelim_judge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfprelimjudge2'])->name('mr_pdfprelim_judge2');
    Route::get('/mr_pdfprelim_judge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfprelimjudge3'])->name('mr_pdfprelim_judge3');
    Route::get('/mr_pdftop_5', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdftop_5'])->name('mr_pdftop_5');

    Route::get('/mr_pdfnational_costume', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfnational_costume'])->name('mr_pdfnational_costume');
    Route::get('/mr_pdfnational_costumejudge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfnational_costumejudge1'])->name('mr_pdfnational_costumejudge1');
    Route::get('/mr_pdfnational_costumejudge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfnational_costumejudge2'])->name('mr_pdfnational_costumejudge2');
    Route::get('/mr_pdfnational_costumejudge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfnational_costumejudge3'])->name('mr_pdfnational_costumejudge3');

    Route::get('/mr_pdfdepartmental_uniform', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfdepartmental_uniform'])->name('mr_pdfdepartmental_uniform');
    Route::get('/mr_pdfdepartmental_uniformjudge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfdepartmental_uniformjudge1'])->name('mr_pdfdepartmental_uniformjudge1');
    Route::get('/mr_pdfdepartmental_uniformjudge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfdepartmental_uniformjudge2'])->name('mr_pdfdepartmental_uniformjudge2');
    Route::get('/mr_pdfdepartmental_uniformjudge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfdepartmental_uniformjudge3'])->name('mr_pdfdepartmental_uniformjudge3');

    Route::get('/mr_pdfswim_wear', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfswim_wear'])->name('mr_pdfswim_wear');
    Route::get('/mr_pdfswim_wearjudge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfswim_wearjudge1'])->name('mr_pdfswim_wearjudge1');
    Route::get('/mr_pdfswim_wearjudge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfswim_wearjudge2'])->name('mr_pdfswim_wearjudge2');
    Route::get('/mr_pdfswim_wearjudge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfswim_wearjudge3'])->name('mr_pdfswim_wearjudge3');
    
    Route::get('/mr_pdfformal_wear', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfformal_wear'])->name('mr_pdfformal_wear');
    Route::get('/mr_pdfformal_wearjudge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfformal_wearjudge1'])->name('mr_pdfformal_wearjudge1');
    Route::get('/mr_pdfformal_wearjudge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfformal_wearjudge2'])->name('mr_pdfformal_wearjudge2');
    Route::get('/mr_pdfformal_wearjudge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfformal_wearjudge3'])->name('mr_pdfformal_wearjudge3');

    Route::get('/mr_pdfqna', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfqna'])->name('mr_pdfqna');
    Route::get('/mr_pdfqnajudge1', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfqnajudge1'])->name('mr_pdfqnajudge1');
    Route::get('/mr_pdfqnajudge2', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfqnajudge2'])->name('mr_pdfqnajudge2');
    Route::get('/mr_pdfqnajudge3', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_pdfqnajudge3'])->name('mr_pdfqnajudge3');
   
    //RESULTS for Ms. LCUAA PRELIMINARIES
    Route::get('/ms_prelim', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_prelim'])->name('ms_prelim');
    Route::get('/ms_national_costume', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_national_costume'])->name('ms_national_costume');
    Route::get('/ms_departmental_uniform', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_departmental_uniform'])->name('ms_departmental_uniform');
    Route::get('/ms_swim_wear', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_swim_wear'])->name('ms_swim_wear');
    Route::get('/ms_formal_wear', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_formal_wear'])->name('ms_formal_wear');
    Route::get('/ms_qna', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_qna'])->name('ms_qna');
    Route::get('/ms_top_5', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_top_5'])->name('ms_top_5');

    //INDIVIDUAL RESULTS
    Route::get('/ms_prelim_judge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_prelimjudge1'])->name('ms_prelim_judge1');
    Route::get('/ms_prelim_judge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_prelimjudge2'])->name('ms_prelim_judge2');
    Route::get('/ms_prelim_judge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_prelimjudge3'])->name('ms_prelim_judge3');

    Route::get('/ms_national_costume_judge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_national_costumejudge1'])->name('ms_national_costume_judge1');
    Route::get('/ms_national_costume_judge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_national_costumejudge2'])->name('ms_national_costume_judge2');
    Route::get('/ms_national_costume_judge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_national_costumejudge3'])->name('ms_national_costume_judge3');

    Route::get('/ms_departmental_uniform_judge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_departmental_uniformjudge1'])->name('ms_departmental_uniform_judge1');
    Route::get('/ms_departmental_uniform_judge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_departmental_uniformjudge2'])->name('ms_departmental_uniform_judge2');
    Route::get('/ms_departmental_uniform_judge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_departmental_uniformjudge3'])->name('ms_departmental_uniform_judge3');

    Route::get('/ms_swim_wear_judge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_swim_wearjudge1'])->name('ms_swim_wear_judge1');
    Route::get('/ms_swim_wear_judge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_swim_wearjudge2'])->name('ms_swim_wear_judge2');
    Route::get('/ms_swim_wear_judge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_swim_wearjudge3'])->name('ms_swim_wear_judge3');

    Route::get('/ms_formal_wear_judge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_formal_wearjudge1'])->name('ms_formal_wear_judge1');
    Route::get('/ms_formal_wear_judge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_formal_wearjudge2'])->name('ms_formal_wear_judge2');
    Route::get('/ms_formal_wear_judge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_formal_wearjudge3'])->name('ms_formal_wear_judge3');

    Route::get('/ms_qna_judge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_qnajudge1'])->name('ms_qna_judge1');
    Route::get('/ms_qna_judge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_qnajudge2'])->name('ms_qna_judge2');
    Route::get('/ms_qna_judge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_qnajudge3'])->name('ms_qna_judge3');

    //PDF Result
    Route::get('/ms_pdfprelim', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfprelim'])->name('ms_pdfprelim');
    Route::get('/ms_pdfprelim_judge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfprelimjudge1'])->name('ms_pdfprelim_judge1');
    Route::get('/ms_pdfprelim_judge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfprelimjudge2'])->name('ms_pdfprelim_judge2');
    Route::get('/ms_pdfprelim_judge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfprelimjudge3'])->name('ms_pdfprelim_judge3');
    Route::get('/ms_pdftop_5', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdftop_5'])->name('ms_pdftop_5');
    Route::get('/ms_to_top_5_rank', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_to_top_5_rank'])->name('ms_to_top_5_rank');

    Route::get('/ms_pdfnational_costume', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfnational_costume'])->name('ms_pdfnational_costume');
    Route::get('/ms_pdfnational_costumejudge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfnational_costumejudge1'])->name('ms_pdfnational_costumejudge1');
    Route::get('/ms_pdfnational_costumejudge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfnational_costumejudge2'])->name('ms_pdfnational_costumejudge2');
    Route::get('/ms_pdfnational_costumejudge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfnational_costumejudge3'])->name('ms_pdfnational_costumejudge3');

    Route::get('/ms_pdfdepartmental_uniform', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfdepartmental_uniform'])->name('ms_pdfdepartmental_uniform');
    Route::get('/ms_pdfdepartmental_uniformjudge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfdepartmental_uniformjudge1'])->name('ms_pdfdepartmental_uniformjudge1');
    Route::get('/ms_pdfdepartmental_uniformjudge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfdepartmental_uniformjudge2'])->name('ms_pdfdepartmental_uniformjudge2');
    Route::get('/ms_pdfdepartmental_uniformjudge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfdepartmental_uniformjudge3'])->name('ms_pdfdepartmental_uniformjudge3');

    Route::get('/ms_pdfswim_wear', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfswim_wear'])->name('ms_pdfswim_wear');
    Route::get('/ms_pdfswim_wearjudge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfswim_wearjudge1'])->name('ms_pdfswim_wearjudge1');
    Route::get('/ms_pdfswim_wearjudge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfswim_wearjudge2'])->name('ms_pdfswim_wearjudge2');
    Route::get('/ms_pdfswim_wearjudge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfswim_wearjudge3'])->name('ms_pdfswim_wearjudge3');

    Route::get('/ms_pdfformal_wear', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfformal_wear'])->name('ms_pdfformal_wear');
    Route::get('/ms_pdfformal_wearjudge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfformal_wearjudge1'])->name('ms_pdfformal_wearjudge1');
    Route::get('/ms_pdfformal_wearjudge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfformal_wearjudge2'])->name('ms_pdfformal_wearjudge2');
    Route::get('/ms_pdfformal_wearjudge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfformal_wearjudge3'])->name('ms_pdfformal_wearjudge3');

    Route::get('/ms_pdfqna', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfqna'])->name('ms_pdfqna');
    Route::get('/ms_pdfqnajudge1', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfqnajudge1'])->name('ms_pdfqnajudge1');
    Route::get('/ms_pdfqnajudge2', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfqnajudge2'])->name('ms_pdfqnajudge2');
    Route::get('/ms_pdfqnajudge3', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_pdfqnajudge3'])->name('ms_pdfqnajudge3');

    //RESULTS for Mr. LCUAA FINAL
    Route::get('/mr_final', [App\Http\Controllers\MrLcuaaFinalReportsController::class, 'mr_final'])->name('mr_final');
    Route::get('/mr_final_judge1', [App\Http\Controllers\MrLcuaaFinalReportsController::class, 'mr_final_judge1'])->name('mr_final_judge1');
    Route::get('/mr_final_judge2', [App\Http\Controllers\MrLcuaaFinalReportsController::class, 'mr_final_judge2'])->name('mr_final_judge2');
    Route::get('/mr_final_judge3', [App\Http\Controllers\MrLcuaaFinalReportsController::class, 'mr_final_judge3'])->name('mr_final_judge3');

    Route::get('/mr_final_score_seeder', [App\Http\Controllers\MrLcuaaFinalReportsController::class, 'mr_final_score_seeder'])->name('mr_final_score_seeder');


    //PDF Result
    Route::get('/mr_pdffinal', [App\Http\Controllers\MrLcuaaFinalReportsController::class, 'mr_pdffinal'])->name('mr_pdffinal');

    //RESULTS for Ms. LCUAA FINAL
    Route::get('/ms_final', [App\Http\Controllers\MsLcuaaFinalReportsController::class, 'ms_final'])->name('ms_final');
    Route::get('/ms_final_judge1', [App\Http\Controllers\MsLcuaaFinalReportsController::class, 'ms_final_judge1'])->name('ms_final_judge1');
    Route::get('/ms_final_judge2', [App\Http\Controllers\MsLcuaaFinalReportsController::class, 'ms_final_judge2'])->name('ms_final_judge2');
    Route::get('/ms_final_judge3', [App\Http\Controllers\MsLcuaaFinalReportsController::class, 'ms_final_judge3'])->name('ms_final_judge3');

    Route::get('/ms_final_score_seeder', [App\Http\Controllers\MsLcuaaFinalReportsController::class, 'ms_final_score_seeder'])->name('ms_final_score_seeder');

    //PDF Result
    Route::get('/ms_pdffinal', [App\Http\Controllers\MsLcuaaFinalReportsController::class, 'ms_pdffinal'])->name('ms_pdffinal');

    //MR. RANKING
    Route::get('/mr_prepageant_rank', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_prepageant_rank'])->name('mr_prepageant_rank');
    Route::get('/mr_rave_wear_rank', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_rave_wear_rank'])->name('mr_rave_wear_rank');
    Route::get('/mr_talent_rank', [App\Http\Controllers\MrLcuaaPrePageantReportsController::class, 'mr_talent_rank'])->name('mr_talent_rank');

    Route::get('/mr_prelim_rank', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_prelim_rank'])->name('mr_prelim_rank');
    Route::get('/mr_national_costume_rank', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_national_costume_rank'])->name('mr_national_costume_rank');
    Route::get('/mr_departmental_uniform_rank', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_departmental_uniform_rank'])->name('mr_departmental_uniform_rank');
    Route::get('/mr_swim_wear_rank', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_swim_wear_rank'])->name('mr_swim_wear_rank');
    Route::get('/mr_formal_wear_rank', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_formal_wear_rank'])->name('mr_formal_wear_rank');
    Route::get('/mr_qna_rank', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_qna_rank'])->name('mr_qna_rank');

    Route::get('/mr_to_top_5_rank', [App\Http\Controllers\MrLcuaaPrelimReportsController::class, 'mr_to_top_5_rank'])->name('mr_to_top_5_rank');

    Route::get('/mr_final_rank', [App\Http\Controllers\MrLcuaaFinalReportsController::class, 'mr_final_rank'])->name('mr_final_rank');

    //MS. RANKING
    Route::get('/ms_prepageant_rank', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_prepageant_rank'])->name('ms_prepageant_rank');
    Route::get('/ms_rave_wear_rank', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_rave_wear_rank'])->name('ms_rave_wear_rank');
    Route::get('/ms_talent_rank', [App\Http\Controllers\MsLcuaaPrePageantReportsController::class, 'ms_talent_rank'])->name('ms_talent_rank');

    Route::get('/ms_prelim_rank', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_prelim_rank'])->name('ms_prelim_rank');
    Route::get('/ms_national_costume_rank', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_national_costume_rank'])->name('ms_national_costume_rank');
    Route::get('/ms_departmental_uniform_rank', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_departmental_uniform_rank'])->name('ms_departmental_uniform_rank');
    Route::get('/ms_swim_wear_rank', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_swim_wear_rank'])->name('ms_swim_wear_rank');
    Route::get('/ms_formal_wear_rank', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_formal_wear_rank'])->name('ms_formal_wear_rank');
    Route::get('/ms_qna_rank', [App\Http\Controllers\MsLcuaaPrelimReportsController::class, 'ms_qna_rank'])->name('ms_qna_rank');

    Route::get('/ms_final_rank', [App\Http\Controllers\MsLcuaaFinalReportsController::class, 'ms_final_rank'])->name('ms_final_rank');

});

// JUDGE
Route::middleware(['auth', 'user-access:judge'])->group(function () {
    Route::get('/judge-app', [App\Http\Controllers\JudgeAppController::class, 'index'])->name('judge.app');

    // Pre-Pageant
    //MS
    Route::get('/judge-app/{stage}/ms-score-board', [App\Http\Controllers\JudgeAppController::class, 'msScoreBoard'])->name('judge.app.ms.score');
    Route::get('/judge-app/{stage}/ms-talent-score-board', [App\Http\Controllers\JudgeAppController::class, 'msTalentScoreBoard'])->name('judge.app.ms.talent.score');
    Route::get('/judge-app/{stage}/ms-ravewear-score-board',[App\Http\Controllers\JudgeAppController::class, 'msRavewearScoreBoard'])->name('judge.app.ms.ravewear.score');

    //MR
    Route::get('/judge-app/{stage}/mr-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrScoreBoard'])->name('judge.app.mr.score');
    Route::get('/judge-app/{stage}/mr-talent-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrTalentScoreBoard'])->name('judge.app.mr.talent.score');
    Route::get('/judge-app/{stage}/mr-ravewear-score-board',[App\Http\Controllers\JudgeAppController::class, 'mrRavewearScoreBoard'])->name('judge.app.mr.ravewear.score');

    // Preliminaries
    //MR
    Route::get('/judge-app/{stage}/mr-prelim-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrPrelimScoreBoard'])->name('judge.app.mr.prelim.score');
    Route::get('/judge-app/{stage}/mr-nationalcostume-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrNationalcostumeScoreBoard'])->name('judge.app.mr.nationalcostume.score');
    Route::get('/judge-app/{stage}/mr-deparmentaluniform-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrDepartmentaluniformScoreBoard'])->name('judge.app.mr.departmentaluniform.score');
    Route::get('/judge-app/{stage}/mr-swimwear-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrSwimwearScoreBoard'])->name('judge.app.mr.swimwear.score');
    Route::get('/judge-app/{stage}/mr-formalwear-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrFormalwearScoreBoard'])->name('judge.app.mr.formalwear.score');
    Route::get('/judge-app/{stage}/mr-qna-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrQnaScoreBoard'])->name('judge.app.mr.qna.score');

    //MS
    Route::get('/judge-app/{stage}/ms-prelim-score-board', [App\Http\Controllers\JudgeAppController::class, 'msPrelimScoreBoard'])->name('judge.app.ms.prelim.score');
    Route::get('/judge-app/{stage}/ms-nationalcostume-score-board', [App\Http\Controllers\JudgeAppController::class, 'msNationalcostumeScoreBoard'])->name('judge.app.ms.nationalcostume.score');
    Route::get('/judge-app/{stage}/ms-deparmentaluniform-score-board', [App\Http\Controllers\JudgeAppController::class, 'msDepartmentaluniformScoreBoard'])->name('judge.app.ms.departmentaluniform.score');
    Route::get('/judge-app/{stage}/ms-swimwear-score-board', [App\Http\Controllers\JudgeAppController::class, 'msSwimwearScoreBoard'])->name('judge.app.ms.swimwear.score');
    Route::get('/judge-app/{stage}/ms-formalwear-score-board', [App\Http\Controllers\JudgeAppController::class, 'msFormalwearScoreBoard'])->name('judge.app.ms.formalwear.score');
    Route::get('/judge-app/{stage}/ms-qna-score-board', [App\Http\Controllers\JudgeAppController::class, 'msQnaScoreBoard'])->name('judge.app.ms.qna.score');

    // Final 6
    //MR
    Route::get('/judge-app/{stage}/mr-final-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrFinalScoreBoard'])->name('judge.app.mr.final.score');

    //MS
    Route::get('/judge-app/{stage}/ms-final-score-board', [App\Http\Controllers\JudgeAppController::class, 'msFinalScoreBoard'])->name('judge.app.ms.final.score');

});

// EXTRA
Route::get('/back', function () {
    return Redirect::back();
})->name('back');
