<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Ms_candidate;
use App\Models\Ms_ranking;
use App\Models\Ms_prelim_score;
use App\Models\Ms_final_rank;

use App\Models\Ms_casualwear_score;
use App\Models\Ms_formalwear_score;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class MsUepPrelimReportsController extends Controller
{

    public function ms_prelim()
    {
        $data['title'] = 'Ms. UEP Preliminaries Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelim', compact('data'));
    }

    public function ms_pdfprelim()
    {
        $data['title'] = 'Ms. UEP Preliminaries Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelim', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_result.pdf');
    }

    public function ms_prelimjudge1()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 1';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelimjudge1', compact('data'));
    }

    public function ms_pdfprelimjudge1()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 1';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelimjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_judge1.pdf');
    }

    public function ms_prelimjudge2()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 2';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelimjudge2', compact('data'));
    }

    public function ms_pdfprelimjudge2()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 2';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelimjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_judge2.pdf');
    }

    public function ms_prelimjudge3()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 3';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelimjudge3', compact('data'));
    }

    public function ms_pdfprelimjudge3()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 3';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelimjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_judge3.pdf');
    }

    public function ms_prelimjudge4()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 4';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelimjudge4', compact('data'));
    }

    public function ms_pdfprelimjudge4()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 4';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelimjudge4', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_judge4.pdf');
    }

    public function ms_prelimjudge5()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 5';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelimjudge5', compact('data'));
    }

    public function ms_pdfprelimjudge5()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 5';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelimjudge5', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_judge5.pdf');
    }

    public function ms_casual_wear()
    {
        $data['title'] = 'Ms. UEP - Casual Wear Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wear', compact('data'));
    }

    public function ms_pdfcasual_wear()
    {
        $data['title'] = 'Ms. UEP - Formal Wear Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wear', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_result.pdf');
    }

    public function ms_casual_wearjudge1()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wearjudge1', compact('data'));
    }

    public function ms_pdfcasual_wearjudge1()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wearjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_judge1.pdf');
    }

    public function ms_casual_wearjudge2()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wearjudge2', compact('data'));
    }

    public function ms_pdfcasual_wearjudge2()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wearjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_judge2.pdf');
    }

    public function ms_casual_wearjudge3()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wearjudge3', compact('data'));
    }

    public function ms_pdfcasual_wearjudge3()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wearjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_judge3.pdf');
    }

    public function ms_casual_wearjudge4()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 4)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wearjudge4', compact('data'));
    }

    public function ms_pdfcasual_wearjudge4()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 4)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wearjudge4', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_judge4.pdf');
    }

    public function ms_casual_wearjudge5()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 5)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wearjudge5', compact('data'));
    }

    public function ms_pdfcasual_wearjudge5()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 5)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wearjudge5', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_judge5.pdf');
    }

    public function ms_formal_wear()
    {
        $data['title'] = 'Ms. UEP - Formal Wear Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wear', compact('data'));
    }

    public function ms_pdfformal_wear()
    {
        $data['title'] = 'Ms. UEP - Formal Wear Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wear', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_result.pdf');
    }

    public function ms_formal_wearjudge1()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wearjudge1', compact('data'));
    }

    public function ms_pdfformal_wearjudge1()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wearjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_judge1.pdf');
    }

    public function ms_formal_wearjudge2()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wearjudge2', compact('data'));
    }

    public function ms_pdfformal_wearjudge2()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wearjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_judge2.pdf');
    }

    public function ms_formal_wearjudge3()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wearjudge3', compact('data'));
    }

    public function ms_pdfformal_wearjudge3()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wearjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_judge3.pdf');
    }

    public function ms_formal_wearjudge4()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 4)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wearjudge4', compact('data'));
    }

    public function ms_pdfformal_wearjudge4()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 4)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wearjudge4', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_judge4.pdf');
    }

    public function ms_formal_wearjudge5()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 4)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wearjudge5', compact('data'));
    }

    public function ms_pdfformal_wearjudge5()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 5)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wearjudge5', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_judge5.pdf');
    }

    //Ranking
    public function ms_prelim_rank()
    {

        for ($x = 2; $x <= 6; $x++) {
            $score = Ms_prelim_score::where('judge_id', $x)
                ->select(DB::raw('casual_wear + formal_wear as score'), 'candidate_id')
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
                        ->update(['prelim' => $rank]);
                    $new_rank = $rank;
                } elseif ($prev_score == $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['prelim' => $prev_rank]);
                    $new_rank = $prev_rank;
                }

                $prev_rank = $new_rank;
                $prev_score = $score->score;
                // print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Ms_ranking::select(DB::raw('SUM(prelim) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 5;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['prelim' => $final_ranking]);
                $new_final_rank = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['prelim' => $prev_final_rank]);
                $new_final_rank = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_rank;
        }
    }

    public function ms_casual_wear_rank()
    {

        for ($x = 2; $x <= 6; $x++) {
            $score = Ms_casualwear_score::where('judge_id', $x)
                ->select(DB::raw('poise + execution + appearance as score'), 'candidate_id')
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
                        ->update(['casual_wear' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['casual_wear' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Ms_ranking::select(DB::raw('SUM(casual_wear) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 5;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['casual_wear' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['casual_wear' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }

    public function ms_formal_wear_rank()
    {

        for ($x = 2; $x <= 6; $x++) {
            $score = Ms_formalwear_score::where('judge_id', $x)
                ->select(DB::raw('elegance + presence + projection + poise as score'), 'candidate_id')
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
                        ->update(['formal_wear' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['formal_wear' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Ms_ranking::select(DB::raw('SUM(formal_wear) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 5;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['formal_wear' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['formal_wear' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }



    public function ms_top_6()
    {
        $data['title'] = 'MS. UEP Top 6 Results';

        $data['final_rank'] = Ms_final_rank::where('to_final', '<=', 6)->get();

        return view('admin.reports.prelim.ms.top6', compact('data'));
    }

    public function ms_pdftop_6()
    {
        $data['title'] = 'MS. UEP Top 6 Results';

        $data['final_rank'] = Ms_final_rank::where('to_final', '<=', 6)->get();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdftop6', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_top_6.pdf');

        // return view('admin.reports.prelim.mr.top6', compact('data'));
    }

    public function ms_active()
    {
        $candidates = Ms_candidate::where('is_active', 1)->get();
        foreach ($candidates as $candidate) {
            print_r("Candidate #" . $candidate->id . "<br>");
        }

        $get_top_6 = Ms_final_rank::select(DB::raw('(prepageant * 0.5) + (prelim * 0.5) as total'), 'candidate_id')
            // ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 1;
        $prev_final_rank = 1;

        foreach ($get_top_6 as $idx => $final_rank) {
            $final_ranking = $idx + 1;
            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['to_final' => $final_ranking]);
                $new_final_rank = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['to_final' => $prev_final_rank]);
                $new_final_rank = $prev_final_rank;
            }

            if ($new_final_rank <= 6) {
                $is_active = Ms_candidate::where('id', $final_rank->candidate_id)
                    ->update(['is_active' => 1]);
            } else {
                $is_active = Ms_candidate::where('id', $final_rank->candidate_id)
                    ->update(['is_active' => 0]);
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_rank;
        }
    }
}
