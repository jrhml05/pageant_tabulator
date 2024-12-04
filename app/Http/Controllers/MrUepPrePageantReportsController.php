<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Score;
use App\Models\Mr_candidate;
use App\Models\Mr_ranking;
use App\Models\Mr_prepageant_score;
use App\Models\Mr_talent_score;
use App\Models\Mr_ravewear_score;

use App\Models\Mr_final_rank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class MrUepPrePageantReportsController extends Controller
{

    public function mr_prepageant()
    {
        $data['title'] = 'Pre-pageant Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        return view('admin.reports.prepageant.mr.prepageant', compact('data'));
    }

    public function mr_pdfprepageant()
    {
        $data['title'] = 'Pre-pageant Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.pdfprepageant', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prepageant_result.pdf');
    }

    public function mr_prepageantjudge1()
    {

        $data['title'] = 'Mr. UEP Pre-pageant Results Judge 1';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prepageant.mr.prepageantjudge1', compact('data'));
    }

    public function mr_pdfprepageantjudge1()
    {

        $data['title'] = 'Mr. UEP Pre-pageant Results Judge 1';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.pdfprepageantjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prepageant_judge1.pdf');
    }

    public function mr_prepageantjudge2()
    {

        $data['title'] = 'Mr. UEP Pre-pageant Results Judge 2';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prepageant.mr.prepageantjudge2', compact('data'));
    }

    public function mr_pdfprepageantjudge2()
    {

        $data['title'] = 'Mr. UEP Pre-pageant Results Judge 2';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.pdfprepageantjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prepageant_judge2.pdf');
    }

    public function mr_prepageantjudge3()
    {

        $data['title'] = 'Mr. UEP Pre-pageant Results Judge 3';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prepageant.mr.prepageantjudge3', compact('data'));
    }

    public function mr_pdfprepageantjudge3()
    {

        $data['title'] = 'Mr. UEP Pre-pageant Results Judge 3';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.pdfprepageantjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prepageant_judge3.pdf');
    }

   

    public function mr_rave_wear()
    {
        $data['title'] = 'Mr. UEP - Rave Wear Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();
        
        $data['final_rank'] = Mr_final_rank::all();

        return view('admin.reports.prepageant.mr.rave_wear.rave_wear', compact('data'));
    }

    public function mr_pdfrave_wear()
    {
        $data['title'] = 'Mr. UEP - Rave Wear Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.rave_wear.pdfrave_wear', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_rave_wear_result.pdf');
    }

    public function mr_rave_wearjudge1()
    {

        $data['title'] = 'Mr. UEP - Rave Wear Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prepageant.mr.rave_wear.rave_wearjudge1', compact('data'));
    }

    public function mr_pdfrave_wearjudge1()
    {

        $data['title'] = 'Mr. UEP - Rave Wear Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.rave_wear.pdfrave_wearjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_rave_wear_judge1.pdf');
    }

    public function mr_rave_wearjudge2()
    {

        $data['title'] = 'Mr. UEP - Rave Wear Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prepageant.mr.rave_wear.rave_wearjudge2', compact('data'));
    }

    public function mr_pdfrave_wearjudge2()
    {

        $data['title'] = 'Mr. UEP - Rave Wear Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.rave_wear.pdfrave_wearjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_rave_wear_judge2.pdf');
    }

    public function mr_rave_wearjudge3()
    {

        $data['title'] = 'Mr. UEP - Rave Wear Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prepageant.mr.rave_wear.rave_wearjudge3', compact('data'));
    }

    public function mr_pdfrave_wearjudge3()
    {

        $data['title'] = 'Mr. UEP - Rave Wear Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.rave_wear.pdfrave_wearjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_rave_wear_judge3.pdf');
    }

    public function mr_talent()
    {
        $data['title'] = 'Mr. UEP - Talent Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        return view('admin.reports.prepageant.mr.talent.talent', compact('data'));
    }

    public function mr_pdftalent()
    {
        $data['title'] = 'Mr. UEP - Talent Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.talent.pdftalent', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_talent_result.pdf');
    }

    public function mr_talentjudge1()
    {

        $data['title'] = 'Mr. UEP - Talent Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prepageant.mr.talent.talentjudge1', compact('data'));
    }

    public function mr_pdftalentjudge1()
    {

        $data['title'] = 'Mr. UEP - Talent Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.talent.pdftalentjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_talent_judge1.pdf');
    }

    public function mr_talentjudge2()
    {

        $data['title'] = 'Mr. UEP - Talent Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prepageant.mr.talent.talentjudge2', compact('data'));
    }

    public function mr_pdftalentjudge2()
    {

        $data['title'] = 'Mr. UEP - Talent Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.talent.pdftalentjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_talent_judge2.pdf');
    }

    public function mr_talentjudge3()
    {

        $data['title'] = 'Mr. UEP - Talent Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prepageant.mr.talent.talentjudge3', compact('data'));
    }

    public function mr_pdftalentjudge3()
    {

        $data['title'] = 'Mr. UEP - Talent Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.mr.talent.pdftalentjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_talent_judge3.pdf');
    }

    //Ranking
    public function mr_prepageant_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Mr_prepageant_score::where('judge_id', $x)
                ->select(DB::raw('rave_wear + talent as score'), 'candidate_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $candidate_id = $score->candidate_id;
                // print_r("Rank: " . $rank . ":" . $candidate_id . "(" . $score->score . ")<br>");

                if ($prev_score > $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['prepageant' => $rank]);
                    $new_rank = $rank;
                } elseif ($prev_score == $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['prepageant' => $prev_rank]);
                    $new_rank = $prev_rank;
                }

                $prev_rank = $new_rank;
                $prev_score = $score->score;
                // print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Mr_ranking::select(DB::raw('SUM(prepageant) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['prepageant' => $final_ranking]);
                $new_final_rank = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['prepageant' => $prev_final_rank]);
                $new_final_rank = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_rank;
        }
    }

    public function mr_rave_wear_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Mr_ravewear_score::where('judge_id', $x)
                ->select(DB::raw('style + creativity + functionality + audience_impact as score'), 'candidate_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $candidate_id = $score->candidate_id;
                print_r("Rank: " . $rank . ":" . $candidate_id . "(" . $score->score . ")<br>");

                if ($prev_score == $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['rave_wear' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['rave_wear' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Mr_ranking::select(DB::raw('SUM(rave_wear) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['rave_wear' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['rave_wear' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }

    public function mr_talent_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Mr_talent_score::where('judge_id', $x)
                ->select(DB::raw('mastery + uniqueness + stage_presence + audience_impact as score'), 'candidate_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $candidate_id = $score->candidate_id;
                print_r("Rank: " . $rank . ":" . $candidate_id . "(" . $score->score . ")<br>");

                if ($prev_score == $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['talent' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['talent' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Mr_ranking::select(DB::raw('SUM(talent) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['talent' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['talent' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }
}
