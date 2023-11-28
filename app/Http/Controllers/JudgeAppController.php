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

    public function msScoreBoard($stage)
    {
        return view('judge_app.prepageant.ms.score-board-screen', compact('stage'));
    }

    public function msProdnumScoreBoard($stage)
    {
        return view('judge_app.prepageant.ms.prodnum-score-board-screen', compact('stage'));
    }

    public function msSportswearScoreBoard($stage)
    {
        return view('judge_app.prepageant.ms.sportswear-score-board-screen', compact('stage'));
    }

    public function msTalentScoreBoard($stage)
    {
        return view('judge_app.prepageant.ms.talent-score-board-screen', compact('stage'));
    }

    public function mrScoreBoard($stage)
    {
        return view('judge_app.prepageant.mr.score-board-screen', compact('stage'));
    }

    public function mrProdnumScoreBoard($stage)
    {
        return view('judge_app.prepageant.mr.prodnum-score-board-screen', compact('stage'));
    }

    public function mrSportswearScoreBoard($stage)
    {
        return view('judge_app.prepageant.mr.sportswear-score-board-screen', compact('stage'));
    }

    public function mrTalentScoreBoard($stage)
    {
        return view('judge_app.prepageant.mr.talent-score-board-screen', compact('stage'));
    }


    public function finalScoreBoard()
    {
        return view('judge_app.final-score-board-screen');
    }
}
