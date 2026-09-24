<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Ms_candidate;
use App\Models\Ms_final_score;
use App\Models\Ms_ranking;
use App\Models\Ms_final_rank;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class MsLcuaaFinalReportsController extends Controller
{

    public function ms_final()
    {
        $data['title'] = 'Finals Results';

        $data['candidate'] = Ms_candidate::where('is_active', 1)->get();

        $data['rank'] = Ms_ranking::all();

        $data['final_rank'] = Ms_final_rank::all();

        return view('admin.reports.final.ms.final', compact('data'));
    }

    public function ms_pdffinal()
    {
        $data['title'] = 'Finals Results';

        $data['candidate'] = Ms_candidate::where('is_active', 1)->get();

        $data['rank'] = Ms_ranking::all();

        $data['final_rank'] = Ms_final_rank::all();

        $pdf = PDF::loadView('admin.reports.final.ms.pdffinal', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('Ms_final_result.pdf');
    }

    public function ms_final_judge1()
    {

        $data['title'] = 'Ms. LCUAA Finals Results Judge 1';

        $data['candidate'] = Ms_candidate::where('is_active', 1)->get();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.final.ms.finaljudge1', compact('data'));
    }

    public function ms_final_judge2()
    {

        $data['title'] = 'Ms. LCUAA Finals Results Judge 2';

        $data['candidate'] = Ms_candidate::where('is_active', 1)->get();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.final.ms.finaljudge2', compact('data'));
    }

    public function ms_final_judge3()
    {

        $data['title'] = 'Ms. LCUAA Finals Results Judge 3';

        $data['candidate'] = Ms_candidate::where('is_active', 1)->get();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.final.ms.finaljudge3', compact('data'));
    }

    public function ms_final_score_seeder(){
        Artisan::call('db:seed', [
            '--class' => 'MsFinalScoreSeeder', 
        ]);

        // Optional: Return a response
        return response()->json(['message' => 'Seeder executed successfully.']);
    }

    //Ranking
    public function ms_final_rank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Ms_final_score::where('judge_id', $x)
                ->select(DB::raw('wit + projection + stage_presence + overall_impact as score'), 'candidate_id')
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

        $get_active_candidate = Ms_candidate::where('is_active', 1)
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->toArray();

        $get_rank = Ms_ranking::select(DB::raw('SUM(final) as total'), 'candidate_id')
            ->whereIn('candidate_id', $get_active_candidate)
            ->groupBy('candidate_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
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
