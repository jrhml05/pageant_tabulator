<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Ms_candidate;
use App\Models\Ms_final_score;
use App\Models\Ms_ranking;
use App\Models\Ms_final_rank;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class MsUepFinalReportsController extends Controller
{

    public function ms_final()
    {
        $data['title'] = 'Finals Results';

        $data['candidate'] = Ms_candidate::where('is_active', 1)->get();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.final.ms.final', compact('data'));
    }

    public function ms_pdffinal()
    {
        $data['title'] = 'Finals Results';

        $data['candidate'] = Ms_candidate::where('is_active', 1)->get();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.final.ms.pdffinal', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('Ms_final_result.pdf');
    }

    public function ms_finaljudge1()
    {

        $data['title'] = 'Ms. UEP Finals Results Judge 1';

        $data['candidate'] = Ms_candidate::where('is_active', 1)->get();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.final.ms.finaljudge1', compact('data'));
    }

    public function ms_pdffinaljudge1()
    {

        $data['title'] = 'Ms. UEP Finals Results Judge 1';

        $data['candidate'] = Ms_candidate::where('is_active', 1)->get();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.final.ms.pdffinaljudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('Ms_final_judge1.pdf');
    }

    //Ranking
    public function ms_final_rank()
    {

        for ($x = 2; $x <= 6; $x++) {
            $score = Ms_final_score::where('judge_id', $x)
                ->select(DB::raw('beauty + intelligence as score'), 'candidate_id')
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
                        ->update(['final' => $rank]);
                    $new_rank = $rank;
                } elseif ($prev_score == $score->score) {
                    $update_rank = Ms_ranking::where('judge_id', $x)
                        ->where('candidate_id', $candidate_id)
                        ->update(['final' => $prev_rank]);
                    $new_rank = $prev_rank;
                }

                $prev_rank = $new_rank;
                $prev_score = $score->score;
                // print_r($prev_rank . "<br>");
            }
        }

        $get_active_candidate = Ms_candidate::where('is_active', 1)->get()
            ->orderBy('id', 'asc');

        foreach ($get_active_candidate as $active_candidate) {

            $get_rank = Ms_ranking::select(DB::raw('SUM(final) as total'), $active_candidate->id)
                ->groupBy('candidate_id')
                ->orderBy('total', 'asc')
                ->get();

            $prev_total_rank = 5;
            $prev_final_rank = 1;

            foreach ($get_rank as $idx => $final_rank) {
                $final_ranking = $idx + 1;

                if ($prev_total_rank < $final_rank->total) {
                    $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                        ->update(['final' => $final_ranking]);
                    $new_final_rank = $final_ranking;
                } elseif ($prev_total_rank == $final_rank->total) {
                    $update_final_rank = Ms_final_rank::where('candidate_id', $final_rank->candidate_id)
                        ->update(['final' => $prev_final_rank]);
                    $new_final_rank = $prev_final_rank;
                }

                $prev_total_rank = $final_rank->total;
                $prev_final_rank = $new_final_rank;
            }
        }
    }

    public function ms_active()
    {
        $candidates = Ms_candidate::where('is_active', 1)->get();
        foreach ($candidates as $candidate) {
            print_r("Candidate #" . $candidate->id . "<br>");
        }
    }
}
