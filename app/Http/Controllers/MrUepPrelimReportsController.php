<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Mr_candidate;
use App\Models\Mr_deptuni_score;
use App\Models\Mr_prelim_score;
use App\Models\Mr_ranking;
use App\Models\Mr_final_rank;
use App\Models\Mr_formalwear_score;
use App\Models\Mr_natlcost_score;
use App\Models\Mr_qna_score;
use App\Models\Mr_swimwear_score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class MrUepPrelimReportsController extends Controller
{

    public function mr_prelim()
    {
        $data['title'] = 'Preliminaries Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        return view('admin.reports.prelim.mr.prelim', compact('data'));
    }

    public function mr_pdfprelim()
    {
        $data['title'] = 'Preliminaries Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.pdfprelim', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prelim_result.pdf');
    }

    public function mr_prelimjudge1()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 1';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.prelimjudge1', compact('data'));
    }

    

    public function mr_prelimjudge2()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 2';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.prelimjudge2', compact('data'));
    }

    public function mr_national_costume()
    {
        $data['title'] = 'Mr. UEP - National Costume Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        return view('admin.reports.prelim.mr.national_costume.national_costume', compact('data'));
    }

    public function mr_pdfnational_costume()
    {
        $data['title'] = 'Mr. UEP - National Costume Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.national_costume.pdfnational_costume', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_national_costume_result.pdf');
    }

    public function mr_national_costumejudge1()
    {

        $data['title'] = 'Mr. UEP - National Costume Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.national_costume.national_costumejudge1', compact('data'));
    }

    public function mr_national_costumejudge2()
    {

        $data['title'] = 'Mr. UEP - National Costume Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.national_costume.national_costumejudge2', compact('data'));
    }
    public function mr_national_costumejudge3()
    {

        $data['title'] = 'Mr. UEP - National Costume Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.national_costume.national_costumejudge3', compact('data'));
    }

    public function mr_prelimjudge3()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 3';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.prelimjudge3', compact('data'));
    }

    public function mr_departmental_uniform()
    {
        $data['title'] = 'Mr. UEP - Departmental Uniform Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        return view('admin.reports.prelim.mr.departmental_uniform.departmental_uniform', compact('data'));
    }

    public function mr_pdfdepartmental_uniform()
    {
        $data['title'] = 'Mr. UEP - Departmental Uniform Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.departmental_uniform.pdfdepartmental_uniform', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('Mr_departmental_uniform_result.pdf');
    }

    public function mr_departmental_uniformjudge1()
    {

        $data['title'] = 'Mr. UEP - Departmental Uniform Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.departmental_uniform.departmental_uniformjudge1', compact('data'));
    }

    public function mr_departmental_uniformjudge2()
    {

        $data['title'] = 'Mr. UEP - Departmental Uniform Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.departmental_uniform.departmental_uniformjudge2', compact('data'));
    }

    public function mr_departmental_uniformjudge3()
    {

        $data['title'] = 'Mr. UEP - Departmental Uniform Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.departmental_uniform.departmental_uniformjudge3', compact('data'));
    }

    public function Mr_swim_wear()
    {
        $data['title'] = 'Mr. UEP - Swim Wear Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        return view('admin.reports.prelim.mr.swim_wear.swim_wear', compact('data'));
    }

    public function Mr_pdfswim_wear()
    {
        $data['title'] = 'Mr. UEP - Swim Wear Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.swim_wear.pdfswim_wear', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('Mr_swim_wear_result.pdf');
    }

    public function Mr_swim_wearjudge1()
    {

        $data['title'] = 'Mr. UEP - Swim Wear Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.swim_wear.swim_wearjudge1', compact('data'));
    }

    public function Mr_swim_wearjudge2()
    {

        $data['title'] = 'Mr. UEP - Swim Wear Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.swim_wear.swim_wearjudge2', compact('data'));
    }

    public function Mr_swim_wearjudge3()
    {

        $data['title'] = 'Mr. UEP - Swim Wear Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.swim_wear.swim_wearjudge3', compact('data'));
    }

    public function mr_formal_wear()
    {
        $data['title'] = 'Mr. UEP - Formal Wear Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        return view('admin.reports.prelim.mr.formal_wear.formal_wear', compact('data'));
    }

    public function mr_pdfformal_wear()
    {
        $data['title'] = 'Mr. UEP - Formal Wear Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.formal_wear.pdfformal_wear', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_formal_wear_result.pdf');
    }

    public function mr_formal_wearjudge1()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.formal_wear.formal_wearjudge1', compact('data'));
    }

    public function mr_formal_wearjudge2()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.formal_wear.formal_wearjudge2', compact('data'));
    }

    public function mr_formal_wearjudge3()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.formal_wear.formal_wearjudge3', compact('data'));
    }

    public function mr_qna()
    {
        $data['title'] = 'mr. UEP - Casual Q&A Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        return view('admin.reports.prelim.mr.qna.qna', compact('data'));
    }

    public function mr_pdfqna()
    {
        $data['title'] = 'Mr. UEP - Casual Q&A Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $data['final_rank'] = Mr_final_rank::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.qna.pdfqna', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('Mr_qna_result.pdf');
    }

    public function mr_qnajudge1()
    {

        $data['title'] = 'Mr. UEP - Casual Q&A Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.qna.qnajudge1', compact('data'));
    }

    public function mr_qnajudge2()
    {

        $data['title'] = 'Mr. UEP - Casual Q&A Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.qna.qnajudge2', compact('data'));
    }

    public function mr_qnajudge3()
    {

        $data['title'] = 'Mr. UEP - Casual Q&A Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.qna.qnajudge3', compact('data'));
    }

    //Ranking
    public function mr_prelim_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Mr_prelim_score::where('judge_id', $x)
                ->select(DB::raw('national_costume + dept_uniform + swim_wear + formal_wear + qna as score'), 'candidate_id')
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
                        ->update(['pageant' => $rank]);
                    $new_rank = $rank;
                } elseif ($prev_score == $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['pageant' => $prev_rank]);
                    $new_rank = $prev_rank;
                }

                $prev_rank = $new_rank;
                $prev_score = $score->score;
                // print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Mr_ranking::select(DB::raw('SUM(pageant) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['pageant' => $final_ranking]);
                $new_final_rank = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['pageant' => $prev_final_rank]);
                $new_final_rank = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_rank;
        }

        //for top 6 rank
        // $top_six_rank = Mr_final_rank::select('prepageant ')->limit(6)->get()->toArray();
    }

    public function mr_national_costume_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Mr_natlcost_score::where('judge_id', $x)
                ->select(DB::raw('design + stage_presence + poise_bearing + overall_impact as score'), 'candidate_id')
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
                        ->update(['national_costume' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['national_costume' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Mr_ranking::select(DB::raw('SUM(national_costume) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['national_costume' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['national_costume' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }

    public function mr_departmental_uniform_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Mr_deptuni_score::where('judge_id', $x)
                ->select(DB::raw('presentation + figure + beauty_poise + overall_impact as score'), 'candidate_id')
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
                        ->update(['dept_uniform' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['dept_uniform' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Mr_ranking::select(DB::raw('SUM(dept_uniform) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['dept_uniform' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['dept_uniform' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }

    public function mr_swim_wear_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Mr_swimwear_score::where('judge_id', $x)
                ->select(DB::raw('body + poise + stage_presence + audience_impact as score'), 'candidate_id')
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
                        ->update(['swim_wear' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['swim_wear' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Mr_ranking::select(DB::raw('SUM(swim_wear) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['swim_wear' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['swim_wear' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }

    public function mr_formal_wear_rank()
    {

        for ($x = 2; $x <= 6; $x++) {
            $score = Mr_formalwear_score::where('judge_id', $x)
                ->select(DB::raw('beauty + stage_presence + design + overall_impact as score'), 'candidate_id')
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
                        ->update(['formal_wear' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['formal_wear' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Mr_ranking::select(DB::raw('SUM(formal_wear) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['formal_wear' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['formal_wear' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }

    public function mr_qna_rank()
    {

        for ($x = 2; $x <= 6; $x++) {
            $score = Mr_qna_score::where('judge_id', $x)
                ->select(DB::raw('relevance + delivery + content + audience_impact as score'), 'candidate_id')
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
                        ->update(['qna' => $prev_rank]);
                    $new_rank = $prev_rank;
                } elseif ($prev_score > $score->score) {
                    $update_rank = Mr_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['qna' => $rank]);
                    $new_rank = $rank;
                }


                $prev_score = $score->score;
                $prev_rank = $new_rank;

                print_r($prev_rank . "<br>");
            }
        }

        $get_rank = Mr_ranking::select(DB::raw('SUM(qna) as total'), 'candidate_id')
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['qna' => $final_ranking]);
                $new_final_ranking = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['qna' => $prev_final_rank]);
                $new_final_ranking = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_ranking;
        }
    }

    public function mr_top_6()
    {
        $data['title'] = 'MR. UEP Top 6 Results';

        $data['final_rank'] = Mr_final_rank::where('to_final', '<=', 6)->get();

        return view('admin.reports.prelim.mr.top6', compact('data'));
    }

    public function mr_pdftop_6()
    {
        $data['title'] = 'MR. UEP Top 6 Results';

        $data['final_rank'] = Mr_final_rank::where('to_final', '<=', 6)->get();

        $pdf = PDF::loadView('admin.reports.prelim.mr.pdftop6', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_top_6.pdf');

        // return view('admin.reports.prelim.mr.top6', compact('data'));
    }

    public function mr_active()
    {
        $candidates = Mr_candidate::where('is_active', 1)->get();
        foreach ($candidates as $candidate) {
            print_r("Candidate #" . $candidate->id . "<br>");
        }

        $get_top_6 = Mr_final_rank::select(DB::raw('(prepageant * 0.5) + (prelim * 0.5) as total'), 'candidate_id')
            // ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 1;
        $prev_final_rank = 1;

        foreach ($get_top_6 as $idx => $final_rank) {
            $final_ranking = $idx + 1;
            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['to_final' => $final_ranking]);
                $new_final_rank = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = Mr_final_rank::where('candidate_id', $final_rank->candidate_id)
                    ->update(['to_final' => $prev_final_rank]);
                $new_final_rank = $prev_final_rank;
            }

            if ($new_final_rank <= 6) {
                $is_active = Mr_candidate::where('id', $final_rank->candidate_id)
                    ->update(['is_active' => 1]);
            } else {
                $is_active = Mr_candidate::where('id', $final_rank->candidate_id)
                    ->update(['is_active' => 0]);
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_rank;
        }
    }
}
