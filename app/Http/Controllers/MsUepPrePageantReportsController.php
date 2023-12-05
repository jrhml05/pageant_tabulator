<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Score;
use App\Models\Ms_candidate;
use App\Models\Ms_ranking;
use App\Models\Ms_prepageant_score;
use App\Models\Ms_talent_score;
use App\Models\Ms_prodnum_score;
use App\Models\Ms_sportswear_score;

use App\Models\Ms_final_rank;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class MsUepPrePageantReportsController extends Controller
{

    public function ms_prepageant()
    {
        $data['title'] = 'Pre-pageant Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $data['final_rank'] = Ms_final_rank::all();

        return view('admin.reports.prepageant.ms.prepageant', compact('data'));
    }

    public function ms_pdfprepageant()
    {
        $data['title'] = 'Pre-pageant Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.pdfprepageant', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prepageant_result.pdf');
    }

    public function ms_prepageantjudge1()
    {

        $data['title'] = 'Ms. UEP Pre-pageant Results Judge 1';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.prepageantjudge1', compact('data'));
    }

    public function ms_pdfprepageantjudge1()
    {

        $data['title'] = 'Ms. UEP Pre-pageant Results Judge 1';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.pdfprepageantjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prepageant_judge1.pdf');
    }

    public function ms_prepageantjudge2()
    {

        $data['title'] = 'Ms. UEP Pre-pageant Results Judge 2';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.prepageantjudge2', compact('data'));
    }

    public function ms_pdfprepageantjudge2()
    {

        $data['title'] = 'Ms. UEP Pre-pageant Results Judge 2';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.pdfprepageantjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prepageant_judge2.pdf');
    }

    public function ms_prepageantjudge3()
    {

        $data['title'] = 'Ms. UEP Pre-pageant Results Judge 3';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.prepageantjudge3', compact('data'));
    }

    public function ms_pdfprepageantjudge3()
    {

        $data['title'] = 'Ms. UEP Pre-pageant Results Judge 3';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.pdfprepageantjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prepageant_judge3.pdf');
    }

    public function ms_prod_num()
    {
        $data['title'] = 'Ms. UEP - Production Number Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $data['final_rank'] = Ms_final_rank::all();

        return view('admin.reports.prepageant.ms.production_number.prod_num', compact('data'));
    }

    public function ms_pdfprod_num()
    {
        $data['title'] = 'Ms. UEP - Production Number Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.production_number.pdfprod_num', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prod_num_result.pdf');
    }

    public function ms_prod_numjudge1()
    {

        $data['title'] = 'Ms. UEP - Production Number Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.production_number.prod_numjudge1', compact('data'));
    }

    public function ms_pdfprod_numjudge1()
    {

        $data['title'] = 'Ms. UEP - Production Number Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.production_number.pdfprod_numjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prod_num_judge1.pdf');
    }

    public function ms_prod_numjudge2()
    {

        $data['title'] = 'Ms. UEP - Production Number Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.production_number.prod_numjudge2', compact('data'));
    }

    public function ms_pdfprod_numjudge2()
    {

        $data['title'] = 'Ms. UEP - Production Number Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.production_number.pdfprod_numjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prod_num_judge2.pdf');
    }

    public function ms_prod_numjudge3()
    {

        $data['title'] = 'Ms. UEP - Production Number Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.production_number.prod_numjudge3', compact('data'));
    }

    public function ms_pdfprod_numjudge3()
    {

        $data['title'] = 'Ms. UEP - Production Number Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.production_number.pdfprod_numjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prod_num_judge3.pdf');
    }

    public function ms_sports_wear()
    {
        $data['title'] = 'Ms. UEP - Sports Wear Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.sports_wear.sports_wear', compact('data'));
    }

    public function ms_pdfsports_wear()
    {
        $data['title'] = 'Ms. UEP - Sports Wear Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.sports_wear.pdfsports_wear', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_sports_wear_result.pdf');
    }

    public function ms_sports_wearjudge1()
    {

        $data['title'] = 'Ms. UEP - Sports Wear Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.sports_wear.sports_wearjudge1', compact('data'));
    }

    public function ms_pdfsports_wearjudge1()
    {

        $data['title'] = 'Ms. UEP - Sports Wear Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.sports_wear.pdfsports_wearjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_sports_wear_judge1.pdf');
    }

    public function ms_sports_wearjudge2()
    {

        $data['title'] = 'Ms. UEP - Sports Wear Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.sports_wear.sports_wearjudge2', compact('data'));
    }

    public function ms_pdfsports_wearjudge2()
    {

        $data['title'] = 'Ms. UEP - Sports Wear Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.sports_wear.pdfsports_wearjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_sports_wear_judge2.pdf');
    }

    public function ms_sports_wearjudge3()
    {

        $data['title'] = 'Ms. UEP - Sports Wear Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.sports_wear.sports_wearjudge3', compact('data'));
    }

    public function ms_pdfsports_wearjudge3()
    {

        $data['title'] = 'Ms. UEP - Sports Wear Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.sports_wear.pdfsports_wearjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_sports_wear_judge3.pdf');
    }

    public function ms_talent()
    {
        $data['title'] = 'Ms. UEP - Talent Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.talent.talent', compact('data'));
    }

    public function ms_pdftalent()
    {
        $data['title'] = 'Ms. UEP - Talent Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.talent.pdftalent', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_talent_result.pdf');
    }

    public function ms_talentjudge1()
    {

        $data['title'] = 'Ms. UEP - Talent Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.talent.talentjudge1', compact('data'));
    }

    public function ms_pdftalentjudge1()
    {

        $data['title'] = 'Ms. UEP - Talent Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.talent.pdftalentjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_talent_judge1.pdf');
    }

    public function ms_talentjudge2()
    {

        $data['title'] = 'Ms. UEP - Talent Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.talent.talentjudge2', compact('data'));
    }

    public function ms_pdftalentjudge2()
    {

        $data['title'] = 'Ms. UEP - Talent Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.talent.pdftalentjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_talent_judge2.pdf');
    }

    public function ms_talentjudge3()
    {

        $data['title'] = 'Ms. UEP - Talent Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.talent.talentjudge3', compact('data'));
    }

    public function ms_pdftalentjudge3()
    {

        $data['title'] = 'Ms. UEP - Talent Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.talent.pdftalentjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_talent_judge3.pdf');
    }

    //Ranking
    public function ms_prepageant_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Ms_prepageant_score::where('judge_id', $x)
                ->select(DB::raw('production_number + sports_wear + talent as score'), 'candidate_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $candidate_id = $score->candidate_id;
                // print_r("Rank: " . $rank . ":" . $candidate_id . "(" . $score->score . ")<br>");

                if ($prev_score > $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['prepageant' => $rank]);
                    $new_rank = $rank;
                } elseif ($prev_score == $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['prepageant' => $prev_rank]);
                    $new_rank = $prev_rank;
                }

                $prev_rank = $new_rank;
                $prev_score = $score->score;
                // print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Ms_ranking::select(DB::raw('SUM(prepageant) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['prepageant' => $final_ranking]);
                $new_final_rank = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['prepageant' => $prev_final_rank]);
                $new_final_rank = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_rank;
        }
    }

    public function ms_prod_num_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Ms_prodnum_score::where('judge_id', $x)
                ->select(DB::raw('mastery + poise + stage_presence as score'), 'candidate_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $candidate_id = $score->candidate_id;
                print_r("Rank: " . $rank . ":" . $candidate_id . "(" . $score->score . ")<br>");

                if ($prev_score == $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['prod_num' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['prod_num' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Ms_ranking::select(DB::raw('SUM(prod_num) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['prod_num' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['prod_num' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }

    public function ms_sports_wear_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Ms_sportswear_score::where('judge_id', $x)
                ->select(DB::raw('execution + poise + appearance as score'), 'candidate_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $candidate_id = $score->candidate_id;
                print_r("Rank: " . $rank . ":" . $candidate_id . "(" . $score->score . ")<br>");

                if ($prev_score == $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['sports_wear' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['sports_wear' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Ms_ranking::select(DB::raw('SUM(sports_wear) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['sports_wear' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['sports_wear' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }

    public function ms_talent_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Ms_talent_score::where('judge_id', $x)
                ->select(DB::raw('execution + originality + stage_presence as score'), 'candidate_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $candidate_id = $score->candidate_id;
                print_r("Rank: " . $rank . ":" . $candidate_id . "(" . $score->score . ")<br>");

                if ($prev_score == $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['talent' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['talent' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Ms_ranking::select(DB::raw('SUM(talent) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['talent' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['talent' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }
}
