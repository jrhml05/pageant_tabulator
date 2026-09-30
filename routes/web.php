<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JudgeAppController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SegmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::get('/logout', function () {
    Session::flush();
    Auth::logout();

    return redirect('/login');
});

$divisions = array_keys(config('pageant.divisions'));
$segments = array_keys(config('pageant.segments'));

// ADMIN
Route::middleware(['auth', 'user-access:admin'])->group(function () use ($divisions, $segments) {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::controller(SegmentController::class)->prefix('segments/{segment}')->whereIn('segment', $segments)
        ->name('segments.')->group(function () {
            Route::post('/open', 'open')->name('open');
            Route::post('/close', 'close')->name('close');
            Route::delete('/locks/{judge}', 'unlock')->whereNumber('judge')->name('unlock');
        });

    Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
    Route::controller(CandidateController::class)
        ->prefix('candidates/{division}')->whereIn('division', $divisions)->name('candidates.')
        ->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{number}/edit', 'edit')->whereNumber('number')->name('edit');
            Route::put('/{number}', 'update')->whereNumber('number')->name('update');
            Route::delete('/{number}', 'destroy')->whereNumber('number')->name('destroy');
        });

    Route::resource('judges', UserController::class)->except(['show', 'destroy']);

    Route::get('/announcement/print', [ResultsController::class, 'announcementPdf'])->name('announcement.pdf');

    // Per-judge sheets use ?judge=<seat>, where seat 1 is the panel's first judge.
    Route::controller(ResultsController::class)->prefix('results/{division}')->whereIn('division', $divisions)
        ->name('results.')->group(function () use ($segments) {
            Route::get('/round-1', 'roundOne')->name('round1');
            Route::get('/round-1/print', 'roundOnePdf')->name('round1.pdf');
            Route::put('/finalists', 'saveFinalists')->name('finalists');
            Route::post('/finalists/shuffle', 'shuffleAnnouncement')->name('finalists.shuffle');
            Route::get('/{segment}', 'segment')->whereIn('segment', $segments)->name('segment');
            Route::get('/{segment}/print', 'segmentPdf')->whereIn('segment', $segments)->name('segment.pdf');
        });
});

// JUDGE
Route::middleware(['auth', 'user-access:judge'])->controller(JudgeAppController::class)->group(function () use ($segments) {
    Route::get('/judge-app', 'index')->name('judge.app');
    Route::get('/judge-app/status', 'status')->name('judge.status');
    Route::get('/judge-app/{segment}', 'show')->whereIn('segment', $segments)->name('judge.sheet');
});
