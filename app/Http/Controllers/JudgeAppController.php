<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JudgeAppController extends Controller
{
    public function index()
    {
        return view('judge_app.index');
    }

    public function category($stage)
    {
        return view('judge_app.category-screen', compact('stage'));
    }

    public function scoreBoard($stage)
    {
        return view('judge_app.score-board-screen', compact('stage'));

    }

    public function talentScoreBoard($stage)
    {
        return view('judge_app.talent-score-board-screen', compact('stage'));

    }

    public function finalScoreBoard()
    {
        return view('judge_app.final-score-board-screen');

    }
}
