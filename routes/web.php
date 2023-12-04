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

Route::get('/logout', function () {
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
    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('settings');

    //RESULTS for Ms. UEP PREPAGEANT
    Route::get('/ms_prepageant', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_prepageant'])->name('ms_prepageant');
    Route::get('/ms_prod_num', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_prod_num'])->name('ms_prod_num');
    Route::get('/ms_sports_wear', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_sports_wear'])->name('ms_sports_wear');
    Route::get('/ms_talent', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_talent'])->name('ms_talent');

    //INDIVIDUAL RESULTS
    Route::get('/ms_prepageant_judge1', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_prepageantjudge1'])->name('ms_prepageant_judge1');
    Route::get('/ms_prepageant_judge2', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_prepageantjudge2'])->name('ms_prepageant_judge2');
    Route::get('/ms_prepageant_judge3', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_prepageantjudge3'])->name('ms_prepageant_judge3');

    Route::get('/ms_prod_num_judge1', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_prod_numjudge1'])->name('ms_prod_num_judge1');
    Route::get('/ms_prod_num_judge2', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_prod_numjudge2'])->name('ms_prod_num_judge2');
    Route::get('/ms_prod_num_judge3', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_prod_numjudge3'])->name('ms_prod_num_judge3');

    Route::get('/ms_sports_wear_judge1', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_sports_wearjudge1'])->name('ms_sports_wear_judge1');
    Route::get('/ms_sports_wear_judge2', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_sports_wearjudge2'])->name('ms_sports_wear_judge2');
    Route::get('/ms_sports_wear_judge3', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_sports_wearjudge3'])->name('ms_sports_wear_judge3');

    Route::get('/ms_talent_judge1', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_talentjudge1'])->name('ms_talent_judge1');
    Route::get('/ms_talent_judge2', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_talentjudge2'])->name('ms_talent_judge2');
    Route::get('/ms_talent_judge3', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_talentjudge3'])->name('ms_talent_judge3');

    //PDF Result
    Route::get('/ms_pdfprepageant', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfprepageant'])->name('ms_pdfprepageant');
    Route::get('/ms_pdfprepageant_judge1', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfprepageantjudge1'])->name('ms_pdfprepageant_judge1');
    Route::get('/ms_pdfprepageant_judge2', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfprepageantjudge2'])->name('ms_pdfprepageant_judge2');
    Route::get('/ms_pdfprepageant_judge3', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfprepageantjudge3'])->name('ms_pdfprepageant_judge3');

    Route::get('/ms_pdfprod_num', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfprod_num'])->name('ms_pdfprod_num');
    Route::get('/ms_pdfprod_num_judge1', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfprod_numjudge1'])->name('ms_pdfprod_num_judge1');
    Route::get('/ms_pdfprod_num_judge2', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfprod_numjudge2'])->name('ms_pdfprod_num_judge2');
    Route::get('/ms_pdfprod_num_judge3', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfprod_numjudge3'])->name('ms_pdfprod_num_judge3');

    Route::get('/ms_pdfsports_wear', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfsports_wear'])->name('ms_pdfsports_wear');
    Route::get('/ms_pdfsports_wear_judge1', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfsports_wearjudge1'])->name('ms_pdfsports_wear_judge1');
    Route::get('/ms_pdfsports_wear_judge2', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfsports_wearjudge2'])->name('ms_pdfsports_wear_judge2');
    Route::get('/ms_pdfsports_wear_judge3', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdfsports_wearjudge3'])->name('ms_pdfsports_wear_judge3');

    Route::get('/ms_pdftalent', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdftalent'])->name('ms_pdftalent');
    Route::get('/ms_pdftalent_judge1', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdftalentjudge1'])->name('ms_pdftalent_judge1');
    Route::get('/ms_pdftalent_judge2', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdftalentjudge2'])->name('ms_pdftalent_judge2');
    Route::get('/ms_pdftalent_judge3', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_pdftalentjudge3'])->name('ms_pdftalent_judge3');

    //RESULTS for Mr. UEP PREPAGEANT
    Route::get('/mr_prepageant', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_prepageant'])->name('mr_prepageant');
    Route::get('/mr_prod_num', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_prod_num'])->name('mr_prod_num');
    Route::get('/mr_sports_wear', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_sports_wear'])->name('mr_sports_wear');
    Route::get('/mr_talent', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_talent'])->name('mr_talent');

    //INDIVIDUAL RESULTS
    Route::get('/mr_prepageant_judge1', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_prepageantjudge1'])->name('mr_prepageant_judge1');
    Route::get('/mr_prepageant_judge2', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_prepageantjudge2'])->name('mr_prepageant_judge2');
    Route::get('/mr_prepageant_judge3', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_prepageantjudge3'])->name('mr_prepageant_judge3');

    Route::get('/mr_prod_num_judge1', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_prod_numjudge1'])->name('mr_prod_num_judge1');
    Route::get('/mr_prod_num_judge2', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_prod_numjudge2'])->name('mr_prod_num_judge2');
    Route::get('/mr_prod_num_judge3', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_prod_numjudge3'])->name('mr_prod_num_judge3');

    Route::get('/mr_sports_wear_judge1', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_sports_wearjudge1'])->name('mr_sports_wear_judge1');
    Route::get('/mr_sports_wear_judge2', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_sports_wearjudge2'])->name('mr_sports_wear_judge2');
    Route::get('/mr_sports_wear_judge3', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_sports_wearjudge3'])->name('mr_sports_wear_judge3');

    Route::get('/mr_talent_judge1', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_talentjudge1'])->name('mr_talent_judge1');
    Route::get('/mr_talent_judge2', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_talentjudge2'])->name('mr_talent_judge2');
    Route::get('/mr_talent_judge3', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_talentjudge3'])->name('mr_talent_judge3');

    //PDF Result
    Route::get('/mr_pdfprepageant', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfprepageant'])->name('mr_pdfprepageant');
    Route::get('/mr_pdfprepageant_judge1', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfprepageantjudge1'])->name('mr_pdfprepageant_judge1');
    Route::get('/mr_pdfprepageant_judge2', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfprepageantjudge2'])->name('mr_pdfprepageant_judge2');
    Route::get('/mr_pdfprepageant_judge3', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfprepageantjudge3'])->name('mr_pdfprepageant_judge3');

    Route::get('/mr_pdfprod_num', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfprod_num'])->name('mr_pdfprod_num');
    Route::get('/mr_pdfprod_num_judge1', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfprod_numjudge1'])->name('mr_pdfprod_num_judge1');
    Route::get('/mr_pdfprod_num_judge2', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfprod_numjudge2'])->name('mr_pdfprod_num_judge2');
    Route::get('/mr_pdfprod_num_judge3', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfprod_numjudge3'])->name('mr_pdfprod_num_judge3');

    Route::get('/mr_pdfsports_wear', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfsports_wear'])->name('mr_pdfsports_wear');
    Route::get('/mr_pdfsports_wear_judge1', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfsports_wearjudge1'])->name('mr_pdfsports_wear_judge1');
    Route::get('/mr_pdfsports_wear_judge2', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfsports_wearjudge2'])->name('mr_pdfsports_wear_judge2');
    Route::get('/mr_pdfsports_wear_judge3', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdfsports_wearjudge3'])->name('mr_pdfsports_wear_judge3');

    Route::get('/mr_pdftalent', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdftalent'])->name('mr_pdftalent');
    Route::get('/mr_pdftalent_judge1', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdftalentjudge1'])->name('mr_pdftalent_judge1');
    Route::get('/mr_pdftalent_judge2', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdftalentjudge2'])->name('mr_pdftalent_judge2');
    Route::get('/mr_pdftalent_judge3', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_pdftalentjudge3'])->name('mr_pdftalent_judge3');

    //RESULTS for Mr. UEP PRELIMINARIES
    Route::get('/mr_prelim', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_prelim'])->name('mr_prelim');
    Route::get('/mr_casual_wear', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_casual_wear'])->name('mr_casual_wear');
    Route::get('/mr_formal_wear', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_formal_wear'])->name('mr_formal_wear');

    //INDIVIDUAL RESULTS
    Route::get('/mr_prelim_judge1', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_prelimjudge1'])->name('mr_prelim_judge1');
    Route::get('/mr_prelim_judge2', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_prelimjudge2'])->name('mr_prelim_judge2');
    Route::get('/mr_prelim_judge3', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_prelimjudge3'])->name('mr_prelim_judge3');
    Route::get('/mr_prelim_judge4', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_prelimjudge4'])->name('mr_prelim_judge4');
    Route::get('/mr_prelim_judge5', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_prelimjudge5'])->name('mr_prelim_judge5');

    Route::get('/mr_casual_wear_judge1', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_casual_wearjudge1'])->name('mr_casual_wear_judge1');
    Route::get('/mr_casual_wear_judge2', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_casual_wearjudge2'])->name('mr_casual_wear_judge2');
    Route::get('/mr_casual_wear_judge3', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_casual_wearjudge3'])->name('mr_casual_wear_judge3');
    Route::get('/mr_casual_wear_judge4', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_casual_wearjudge4'])->name('mr_casual_wear_judge4');
    Route::get('/mr_casual_wear_judge5', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_casual_wearjudge5'])->name('mr_casual_wear_judge5');

    Route::get('/mr_formal_wear_judge1', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_formal_wearjudge1'])->name('mr_formal_wear_judge1');
    Route::get('/mr_formal_wear_judge2', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_formal_wearjudge2'])->name('mr_formal_wear_judge2');
    Route::get('/mr_formal_wear_judge3', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_formal_wearjudge3'])->name('mr_formal_wear_judge3');
    Route::get('/mr_formal_wear_judge4', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_formal_wearjudge4'])->name('mr_formal_wear_judge4');
    Route::get('/mr_formal_wear_judge5', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_formal_wearjudge5'])->name('mr_formal_wear_judge5');

    //PDF Result
    Route::get('/mr_pdfprelim', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfprelim'])->name('mr_pdfprelim');
    Route::get('/mr_pdfprelim_judge1', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfprelimjudge1'])->name('mr_pdfprelim_judge1');
    Route::get('/mr_pdfprelim_judge2', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfprelimjudge2'])->name('mr_pdfprelim_judge2');
    Route::get('/mr_pdfprelim_judge3', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfprelimjudge3'])->name('mr_pdfprelim_judge3');
    Route::get('/mr_pdfprelim_judge4', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfprelimjudge4'])->name('mr_pdfprelim_judge4');
    Route::get('/mr_pdfprelim_judge5', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfprelimjudge5'])->name('mr_pdfprelim_judge5');

    Route::get('/mr_pdfcasual_wear', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfcasual_wear'])->name('mr_pdfcasual_wear');
    Route::get('/mr_pdfcasual_wearjudge1', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfcasual_wearjudge1'])->name('mr_pdfcasual_wearjudge1');
    Route::get('/mr_pdfcasual_wearjudge2', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfcasual_wearjudge2'])->name('mr_pdfcasual_wearjudge2');
    Route::get('/mr_pdfcasual_wearjudge3', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfcasual_wearjudge3'])->name('mr_pdfcasual_wearjudge3');
    Route::get('/mr_pdfcasual_wearjudge4', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfcasual_wearjudge4'])->name('mr_pdfcasual_wearjudge4');
    Route::get('/mr_pdfcasual_wearjudge5', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfcasual_wearjudge5'])->name('mr_pdfcasual_wearjudge5');

    Route::get('/mr_pdfformal_wear', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfformal_wear'])->name('mr_pdfformal_wear');
    Route::get('/mr_pdfformal_wearjudge1', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfformal_wearjudge1'])->name('mr_pdfformal_wearjudge1');
    Route::get('/mr_pdfformal_wearjudge2', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfformal_wearjudge2'])->name('mr_pdfformal_wearjudge2');
    Route::get('/mr_pdfformal_wearjudge3', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfformal_wearjudge3'])->name('mr_pdfformal_wearjudge3');
    Route::get('/mr_pdfformal_wearjudge4', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfformal_wearjudge4'])->name('mr_pdfformal_wearjudge4');
    Route::get('/mr_pdfformal_wearjudge5', [App\Http\Controllers\MrUepPrelimReportsController::class, 'mr_pdfformal_wearjudge5'])->name('mr_pdfformal_wearjudge5');

    //RESULTS for Ms. UEP PRELIMINARIES
    Route::get('/ms_prelim', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_prelim'])->name('ms_prelim');
    Route::get('/ms_casual_wear', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_casual_wear'])->name('ms_casual_wear');
    Route::get('/ms_formal_wear', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_formal_wear'])->name('ms_formal_wear');

    //INDIVIDUAL RESULTS
    Route::get('/ms_prelim_judge1', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_prelimjudge1'])->name('ms_prelim_judge1');
    Route::get('/ms_prelim_judge2', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_prelimjudge2'])->name('ms_prelim_judge2');
    Route::get('/ms_prelim_judge3', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_prelimjudge3'])->name('ms_prelim_judge3');
    Route::get('/ms_prelim_judge4', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_prelimjudge4'])->name('ms_prelim_judge4');
    Route::get('/ms_prelim_judge5', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_prelimjudge5'])->name('ms_prelim_judge5');

    Route::get('/ms_casual_wear_judge1', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_casual_wearjudge1'])->name('ms_casual_wear_judge1');
    Route::get('/ms_casual_wear_judge2', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_casual_wearjudge2'])->name('ms_casual_wear_judge2');
    Route::get('/ms_casual_wear_judge3', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_casual_wearjudge3'])->name('ms_casual_wear_judge3');
    Route::get('/ms_casual_wear_judge4', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_casual_wearjudge4'])->name('ms_casual_wear_judge4');
    Route::get('/ms_casual_wear_judge5', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_casual_wearjudge5'])->name('ms_casual_wear_judge5');

    Route::get('/ms_formal_wear_judge1', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_formal_wearjudge1'])->name('ms_formal_wear_judge1');
    Route::get('/ms_formal_wear_judge2', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_formal_wearjudge2'])->name('ms_formal_wear_judge2');
    Route::get('/ms_formal_wear_judge3', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_formal_wearjudge3'])->name('ms_formal_wear_judge3');
    Route::get('/ms_formal_wear_judge4', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_formal_wearjudge4'])->name('ms_formal_wear_judge4');
    Route::get('/ms_formal_wear_judge5', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_formal_wearjudge5'])->name('ms_formal_wear_judge5');

    //PDF Result
    Route::get('/ms_pdfprelim', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfprelim'])->name('ms_pdfprelim');
    Route::get('/ms_pdfprelim_judge1', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfprelimjudge1'])->name('ms_pdfprelim_judge1');
    Route::get('/ms_pdfprelim_judge2', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfprelimjudge2'])->name('ms_pdfprelim_judge2');
    Route::get('/ms_pdfprelim_judge3', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfprelimjudge3'])->name('ms_pdfprelim_judge3');
    Route::get('/ms_pdfprelim_judge4', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfprelimjudge4'])->name('ms_pdfprelim_judge4');
    Route::get('/ms_pdfprelim_judge5', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfprelimjudge5'])->name('ms_pdfprelim_judge5');

    Route::get('/ms_pdfcasual_wear', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfcasual_wear'])->name('ms_pdfcasual_wear');
    Route::get('/ms_pdfcasual_wearjudge1', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfcasual_wearjudge1'])->name('ms_pdfcasual_wearjudge1');
    Route::get('/ms_pdfcasual_wearjudge2', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfcasual_wearjudge2'])->name('ms_pdfcasual_wearjudge2');
    Route::get('/ms_pdfcasual_wearjudge3', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfcasual_wearjudge3'])->name('ms_pdfcasual_wearjudge3');
    Route::get('/ms_pdfcasual_wearjudge4', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfcasual_wearjudge4'])->name('ms_pdfcasual_wearjudge4');
    Route::get('/ms_pdfcasual_wearjudge5', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfcasual_wearjudge5'])->name('ms_pdfcasual_wearjudge5');

    Route::get('/ms_pdfformal_wear', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfformal_wear'])->name('ms_pdfformal_wear');
    Route::get('/ms_pdfformal_wearjudge1', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfformal_wearjudge1'])->name('ms_pdfformal_wearjudge1');
    Route::get('/ms_pdfformal_wearjudge2', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfformal_wearjudge2'])->name('ms_pdfformal_wearjudge2');
    Route::get('/ms_pdfformal_wearjudge3', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfformal_wearjudge3'])->name('ms_pdfformal_wearjudge3');
    Route::get('/ms_pdfformal_wearjudge4', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfformal_wearjudge4'])->name('ms_pdfformal_wearjudge4');
    Route::get('/ms_pdfformal_wearjudge5', [App\Http\Controllers\MsUepPrelimReportsController::class, 'ms_pdfformal_wearjudge5'])->name('ms_pdfformal_wearjudge5');

    //MR. RANKING
    Route::get('/mr_prepageant_rank', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_prepageant_rank'])->name('mr_prepageant_rank');
    Route::get('/mr_prod_num_rank', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_prod_num_rank'])->name('mr_prod_num_rank');
    Route::get('/mr_sports_wear_rank', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_sports_wear_rank'])->name('mr_sports_wear_rank');
    Route::get('/mr_talent_rank', [App\Http\Controllers\MrUepPrePageantReportsController::class, 'mr_talent_rank'])->name('mr_talent_rank');

    //MS. RANKING
    Route::get('/ms_prepageant_rank', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_prepageant_rank'])->name('ms_prepageant_rank');
    Route::get('/ms_prod_num_rank', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_prod_num_rank'])->name('ms_prod_num_rank');
    Route::get('/ms_sports_wear_rank', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_sports_wear_rank'])->name('ms_sports_wear_rank');
    Route::get('/ms_talent_rank', [App\Http\Controllers\MsUepPrePageantReportsController::class, 'ms_talent_rank'])->name('ms_talent_rank');

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

    // Pre-Pageant
    //MS
    Route::get('/judge-app/{stage}/ms-score-board', [App\Http\Controllers\JudgeAppController::class, 'msScoreBoard'])->name('judge.app.ms.score');
    Route::get('/judge-app/{stage}/ms-prodnum-score-board', [App\Http\Controllers\JudgeAppController::class, 'msProdnumScoreBoard'])->name('judge.app.ms.prodnum.score');
    Route::get('/judge-app/{stage}/ms-sportswear-score-board', [App\Http\Controllers\JudgeAppController::class, 'msSportswearScoreBoard'])->name('judge.app.ms.sportswear.score');
    Route::get('/judge-app/{stage}/ms-talent-score-board', [App\Http\Controllers\JudgeAppController::class, 'msTalentScoreBoard'])->name('judge.app.ms.talent.score');

    //MR
    Route::get('/judge-app/{stage}/mr-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrScoreBoard'])->name('judge.app.mr.score');
    Route::get('/judge-app/{stage}/mr-prodnum-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrProdnumScoreBoard'])->name('judge.app.mr.prodnum.score');
    Route::get('/judge-app/{stage}/mr-sportswear-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrSportswearScoreBoard'])->name('judge.app.mr.sportswear.score');
    Route::get('/judge-app/{stage}/mr-talent-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrTalentScoreBoard'])->name('judge.app.mr.talent.score');

    // Preliminaries
    //MR
    Route::get('/judge-app/{stage}/mr-prelim-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrPrelimScoreBoard'])->name('judge.app.mr.prelim.score');
    Route::get('/judge-app/{stage}/mr-casualwear-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrCasualwearScoreBoard'])->name('judge.app.mr.casualwear.score');
    Route::get('/judge-app/{stage}/mr-formalwear-score-board', [App\Http\Controllers\JudgeAppController::class, 'mrFormalwearScoreBoard'])->name('judge.app.mr.formalwear.score');

    //MS
    Route::get('/judge-app/{stage}/ms-prelim-score-board', [App\Http\Controllers\JudgeAppController::class, 'msPrelimScoreBoard'])->name('judge.app.ms.prelim.score');
    Route::get('/judge-app/{stage}/ms-casualwear-score-board', [App\Http\Controllers\JudgeAppController::class, 'msCasualwearScoreBoard'])->name('judge.app.ms.casualwear.score');
    Route::get('/judge-app/{stage}/ms-formalwear-score-board', [App\Http\Controllers\JudgeAppController::class, 'msFormalwearScoreBoard'])->name('judge.app.ms.formalwear.score');

    Route::get('/judge-app/final-score-board', [App\Http\Controllers\JudgeAppController::class, 'finalScoreBoard'])->name('judge.app.final.score');
});

// EXTRA
Route::get('/back', function () {
    return Redirect::back();
})->name('back');
