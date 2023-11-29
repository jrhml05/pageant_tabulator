<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Score;
use App\Models\Ms_candidate;
use App\Models\Ms_ranking;

use App\Models\FinalRanking;
use App\Models\PrelimScore;
use App\Models\SemifinalScore;
use App\Models\FinalScore;
use App\Models\SubScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class ReportsController extends Controller
{

    public function ms_prepageant()
    {
        $data['title'] = 'Pre-pageant Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.prepageant', compact('data'));
    }

    public function ms_talent()
    {
        $data['title'] = 'Talent Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prepageant.ms.talent', compact('data'));
    }

    public function ms_pdfprepageant()
    {
        $data['title'] = 'Pre-pageant Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prepageant.ms.pdfprepageant', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('prepageant_result.pdf');
    }

    public function ms_pdftalent()
    {
        $data['title'] = 'Talent Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.pdftalent', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('talent_result.pdf');
    }

    public function prepageantrank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = Score::where('judge_id', $x)
                ->select(DB::raw('talent + beauty + intelligence + poise as score'), 'barangay_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $brgy_id = $score->barangay_id;
                // print_r("Rank: " . $rank . ":" . $brgy_id . "(" . $score->score . ")<br>");

                if ($prev_score > $score->score) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['prepageant_rank' => $rank]);
                } elseif ($prev_score == $score->score) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['prepageant_rank' => $prev_rank]);
                }

                $prev_rank = $rank;
                $prev_score = $score->score;
            }
        }

        $get_rank = Ranking::select(DB::raw('SUM(prepageant_rank) as total'), 'barangay_id')
            ->groupBy('barangay_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['prepageant_rank' => $final_ranking]);
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['prepageant_rank' => $prev_final_rank]);
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $final_ranking;
        }
    }

    public function talentrank()
    {

        for ($x = 2; $x <= 4; $x++) {
            $score = SubScore::where('judge_id', $x)
                ->select(DB::raw('mastery_and_execution + originality + audience_impact as score'), 'barangay_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $brgy_id = $score->barangay_id;
                // print_r("Rank: " . $rank . ":" . $brgy_id . "(" . $score->score . ")<br>");

                if ($prev_score == $score->score) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['talent_rank' => $prev_rank]);
                } elseif ($prev_score > $score->score) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['talent_rank' => $rank]);
                }


                $prev_score = $score->score;
                $prev_rank = $rank;
            }
        }

        $get_rank = Ranking::select(DB::raw('SUM(talent_rank) as total'), 'barangay_id')
            ->groupBy('barangay_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 3;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['talent_rank' => $final_ranking]);
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['talent_rank' => $prev_final_rank]);
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $final_ranking;
        }
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

        return $pdf->stream('prepageant_judge1.pdf');
    }



    public function ms_talentjudge1()
    {

        $data['title'] = 'Talent Results Judge 1';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.talentjudge1', compact('data'));
    }

    public function pdftalentjudge1()
    {

        $data['title'] = 'Talent Results Judge 1';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdftalentjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('talent_judge1.pdf');
    }

    public function top5talent()
    {


        $rank_talent = Ranking::select(DB::raw('SUM(talent_rank) AS RANK'), 'barangay_id')
            ->groupBy('barangay_id')
            ->orderBy('RANK', 'ASC')
            ->take(5)
            ->get();
        foreach ($rank_talent as $rank) {
            print_r("BARANGAY: " . $rank->barangay_id . " - Rank: " . $rank->RANK . "<BR>");
        }
    }

    public function preliminaries()
    {
        $data['title'] = 'Preliminaries Results';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.prelim', compact('data'));
    }

    public function prelimjudge1()
    {
        $data['title'] = 'Preliminaries Judge 1';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.prelimjudge1', compact('data'));
    }

    public function prelimjudge2()
    {
        $data['title'] = 'Preliminaries Judge 2';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.prelimjudge2', compact('data'));
    }

    public function prelimjudge3()
    {
        $data['title'] = 'Preliminaries Judge 3';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.prelimjudge3', compact('data'));
    }

    public function prelimjudge4()
    {
        $data['title'] = 'Preliminaries Judge 4';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.prelimjudge4', compact('data'));
    }

    public function prelimjudge5()
    {
        $data['title'] = 'Preliminaries Judge 5';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.prelimjudge5', compact('data'));
    }

    public function swimsuit()
    {
        $data['title'] = 'Preliminaries Results';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.swimsuit', compact('data'));
    }

    public function eveninggown()
    {
        $data['title'] = 'Preliminaries Results';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.eveninggown', compact('data'));
    }

    public function putscoresprelim()
    {
        $prelim_scores = PrelimScore::where('judge_id', 5)->inRandomOrder()->get();

        foreach ($prelim_scores as $scores) {
            $beauty = rand(19, 40);
            $poise = rand(9, 20);
            $swimsuit = rand(9, 20);
            $gown = rand(9, 20);
            print_r("BEAUTY -> " . $beauty . " || " . "POISE -> " . $poise . " || " . "SWIMSUIT -> " . $swimsuit . " || " . "EVENING GOWN -> " . $gown . "<br>");

            $update_beauty = PrelimScore::where('judge_id', $scores->judge_id)
                ->where("barangay_id", $scores->barangay_id)
                ->update(['beauty' => $beauty]);

            $update_poise = PrelimScore::where('judge_id', $scores->judge_id)
                ->where("barangay_id", $scores->barangay_id)
                ->update(['poise' => $poise]);

            $update_swimsuit = PrelimScore::where('judge_id', $scores->judge_id)
                ->where("barangay_id", $scores->barangay_id)
                ->update(['swimsuit' => $swimsuit]);

            $update_gown = PrelimScore::where('judge_id', $scores->judge_id)
                ->where("barangay_id", $scores->barangay_id)
                ->update(['evening_gown' => $gown]);
        }
    }

    public function prelimrank()
    {
        //ranking per judge
        for ($x = 2; $x <= 6; $x++) {
            $score = PrelimScore::where('judge_id', $x)
                ->select(DB::raw('beauty + poise + swimsuit + evening_gown as score'), 'barangay_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $brgy_id = $score->barangay_id;
                // print_r("Rank: " . $rank . ":" . $brgy_id . "(" . $score->score . ")<br>");

                if ($prev_score > $score->score) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['prelim_rank' => $rank]);
                    $new_rank = $rank;
                } elseif ($prev_score == $score->score) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['prelim_rank' => $prev_rank]);
                    $new_rank = $prev_rank;
                }

                $prev_rank = $new_rank;
                $prev_score = $score->score;
            }
        }

        //ranking for prelim
        $get_rank = Ranking::select(DB::raw('SUM(prelim_rank) as total'), 'barangay_id')
            ->groupBy('barangay_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 5;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['prelim_rank' => $final_ranking]);
                $new_prelim_rank = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['prelim_rank' => $prev_final_rank]);
                $new_prelim_rank = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_prelim_rank;
        }

        //final ranking

        $get_rank_for_semis = FinalRanking::select(DB::raw('(prepageant_rank * 0.4) + (prelim_rank * 0.6) as for_semi'), 'barangay_id')
            ->orderBy('for_semi', 'asc')
            ->get();

        $prev_total_semi_rank = 1;
        $prev_final_semi_rank = 1;

        foreach ($get_rank_for_semis as $i => $for_semi_rank) {

            $for_top12_ranking = $i + 1;

            if ($prev_total_semi_rank < $for_semi_rank->for_semi) {
                $update_final_rank = FinalRanking::where('barangay_id', $for_semi_rank->barangay_id)
                    ->update(['for_semi_rank' => $for_top12_ranking]);

                $new_final_rank = $for_top12_ranking;

                if ($for_top12_ranking > 12) {
                    $update_active = Barangay::where('id', $for_semi_rank->barangay_id)
                        ->update(['is_active' => 0]);
                }
            } elseif ($prev_total_semi_rank == $for_semi_rank->for_semi) {
                $update_final_rank = FinalRanking::where('barangay_id', $for_semi_rank->barangay_id)
                    ->update(['for_semi_rank' => $prev_final_semi_rank]);

                $new_final_rank = $prev_final_semi_rank;

                if ($prev_final_semi_rank > 12) {
                    $update_active = Barangay::where('id', $for_semi_rank->barangay_id)
                        ->update(['is_active' => 0]);
                }
            }
            $prev_total_semi_rank = $for_semi_rank->for_semi;
            $prev_final_semi_rank = $new_final_rank;

            print_r("BARANGAY: " . $for_semi_rank->barangay_id . "(" . $for_semi_rank->for_semi . ") - RANK #" . $prev_final_semi_rank . "<br>");
        }
    }

    public function swimsuitrank()
    {
        //ranking per judge
        for ($x = 2; $x <= 6; $x++) {
            $score = PrelimScore::where('judge_id', $x)
                ->select('swimsuit', 'barangay_id')
                ->orderBy('swimsuit', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 20;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $brgy_id = $score->barangay_id;

                if ($prev_score > $score->swimsuit) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['swim_suit_rank' => $rank]);
                    $new_rank = $rank;
                    print_r("Rank: " . $rank . ":" . $brgy_id . "(" . $score->swimsuit . ")<br>");
                } elseif ($prev_score == $score->swimsuit) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['swim_suit_rank' => $prev_rank]);

                    $new_rank = $prev_rank;
                    print_r("Rank: " . $prev_rank . ":" . $brgy_id . "(" . $score->swimsuit . ")<br>");
                }

                $prev_rank = $new_rank;
                $prev_score = $score->swimsuit;
            }
        }

        //ranking for swimsuit
        $get_rank = Ranking::select(DB::raw('SUM(swim_suit_rank) as total'), 'barangay_id')
            ->groupBy('barangay_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 5;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['swim_suit_rank' => $final_ranking]);
                $new_final_rank = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['swim_suit_rank' => $prev_final_rank]);
                $new_final_rank = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_rank;
        }
    }

    public function eveninggownrank()
    {
        //ranking per judge
        for ($x = 2; $x <= 6; $x++) {
            $score = PrelimScore::where('judge_id', $x)
                ->select('evening_gown', 'barangay_id')
                ->orderBy('evening_gown', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 20;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $brgy_id = $score->barangay_id;
                // print_r("Rank: " . $rank . ":" . $brgy_id . "(" . $score->score . ")<br>");

                if ($prev_score > $score->evening_gown) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['evening_gown_rank' => $rank]);

                    $new_rank = $rank;
                } elseif ($prev_score == $score->evening_gown) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['evening_gown_rank' => $prev_rank]);

                    $new_rank = $prev_rank;
                }

                $prev_rank = $new_rank;
                $prev_score = $score->evening_gown;
            }
        }

        //ranking for evening gown
        $get_rank = Ranking::select(DB::raw('SUM(evening_gown_rank) as total'), 'barangay_id')
            ->groupBy('barangay_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 5;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['evening_gown_rank' => $final_ranking]);
                $new_final_rank = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['evening_gown_rank' => $prev_final_rank]);
                $new_final_rank = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_rank;
        }
    }

    public function pdfprelim()
    {
        $data['title'] = 'Preliminaries Results';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfprelim', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('prelim_result.pdf');
    }

    public function pdfprelimjudge1()
    {
        $data['title'] = 'Preliminaries Results Judge 1';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfprelimjudge1', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('prelim_result_judge1.pdf');
    }

    public function pdfprelimjudge2()
    {
        $data['title'] = 'Preliminaries Results Judge 2';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfprelimjudge2', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('prelim_result_judge2.pdf');
    }

    public function pdfprelimjudge3()
    {
        $data['title'] = 'Preliminaries Results Judge 3';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfprelimjudge3', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('prelim_result_judge3.pdf');
    }

    public function pdfprelimjudge4()
    {
        $data['title'] = 'Preliminaries Results Judge 4';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfprelimjudge4', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('prelim_result_judge4.pdf');
    }

    public function pdfprelimjudge5()
    {
        $data['title'] = 'Preliminaries Results Judge 5';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfprelimjudge5', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('prelim_result_judge5.pdf');
    }

    public function pdfswimsuit()
    {
        $data['title'] = 'BEST in Swimsuit Result';

        $data['final_rank'] = FinalRanking::all();

        $pdf = PDF::loadView('admin.reports.pdfswimsuit', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('swimsuit_result.pdf');
    }

    public function pdfgown()
    {
        $data['title'] = 'BEST in Evening Gown Result';

        $data['final_rank'] = FinalRanking::all();

        $pdf = PDF::loadView('admin.reports.pdfgown', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('gown_result.pdf');
    }

    public function top12()
    {

        $data['title'] = 'TOP 12';

        $data['final_rank'] = FinalRanking::all();

        return view('admin.reports.top12', compact('data'));
    }

    public function pdftop12()
    {
        $data['title'] = 'TOP 12';

        $data['final_rank'] = FinalRanking::inRandomOrder()->limit(24)->get();

        $pdf = PDF::loadView('admin.reports.pdftop12', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('top12_result.pdf');
    }

    public function putsemiscore()
    {

        $semifinal_scores = SemifinalScore::inRandomOrder()->get();

        foreach ($semifinal_scores as $scores) {

            $beauty = rand(19, 40);
            $poise = rand(9, 30);
            $intelligence = rand(9, 30);

            print_r("CANDIDATE: " . $scores->barangay_id . " --- BEAUTY -> " . $beauty . " || " . "POISE -> " . $poise . " || " . "INTELLIGENCE -> " . $intelligence . "<br>");

            $update_beauty = SemifinalScore::where('judge_id', $scores->judge_id)
                ->where("barangay_id", $scores->barangay_id)
                ->update(['beauty' => $beauty]);

            $update_poise = SemifinalScore::where('judge_id', $scores->judge_id)
                ->where("barangay_id", $scores->barangay_id)
                ->update(['poise' => $poise]);

            $update_intelligence = SemifinalScore::where('judge_id', $scores->judge_id)
                ->where("barangay_id", $scores->barangay_id)
                ->update(['intelligence' => $intelligence]);
        }
    }

    public function semifinal()
    {
        $data['title'] = 'SEMI FINALS';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.semifinal', compact('data'));
    }

    public function semifinalrank()
    {
        //ranking per judge
        for ($x = 2; $x <= 6; $x++) {
            $score = SemifinalScore::where('judge_id', $x)
                ->select(DB::raw('beauty + poise + intelligence as score'), 'barangay_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $brgy_id = $score->barangay_id;
                // print_r("Rank: " . $rank . ":" . $brgy_id . "(" . $score->score . ")<br>");

                if ($prev_score > $score->score) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['semi_rank' => $rank]);
                    $new_rank = $rank;
                } elseif ($prev_score == $score->score) {
                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['semi_rank' => $prev_rank]);
                    $new_rank = $prev_rank;
                }

                $prev_rank = $new_rank;
                $prev_score = $score->score;
            }
        }

        //ranking for semis
        $get_rank = Ranking::whereNotNull('semi_rank')->select(DB::raw('SUM(semi_rank) as total'), 'barangay_id')
            ->groupBy('barangay_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 5;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['semi_rank' => $final_ranking]);

                $new_final_rank = $final_ranking;

                if ($final_ranking > 5) {
                    $update_active = Barangay::where('id', $final_rank->barangay_id)
                        ->update(['is_active' => 0]);
                }
            } elseif ($prev_total_rank == $final_rank->total) {
                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['semi_rank' => $prev_final_rank]);

                $new_final_rank = $prev_final_rank;

                if ($prev_final_rank > 5) {
                    $update_active = Barangay::where('id', $final_rank->barangay_id)
                        ->update(['is_active' => 0]);
                }
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_rank;
        }
    }

    public function pdfsemifinal()
    {
        $data['title'] = 'SEMI FINALS';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfsemis', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('semis_result.pdf');
    }

    public function semijudge1()
    {
        $data['title'] = 'Semis Judge 1';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.semifinaljudge1', compact('data'));
    }

    public function semijudge2()
    {
        $data['title'] = 'Semis Judge 2';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.semifinaljudge2', compact('data'));
    }

    public function semijudge3()
    {
        $data['title'] = 'Semis Judge 3';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.semifinaljudge3', compact('data'));
    }

    public function semijudge4()
    {
        $data['title'] = 'Semis Judge 4';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.semifinaljudge4', compact('data'));
    }

    public function semijudge5()
    {
        $data['title'] = 'Semis Judge 5';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.semifinaljudge5', compact('data'));
    }

    public function pdfsemijudge1()
    {
        $data['title'] = 'Semis Judge 1';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfsemisjudge1', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('semis_result_judge1.pdf');
    }

    public function pdfsemijudge2()
    {
        $data['title'] = 'Semis Judge 2';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfsemisjudge2', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('semis_result_judge2.pdf');
    }

    public function pdfsemijudge3()
    {
        $data['title'] = 'Semis Judge 3';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfsemisjudge3', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('semis_result_judge3.pdf');
    }

    public function pdfsemijudge4()
    {
        $data['title'] = 'Semis Judge 4';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfsemisjudge4', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('semis_result_judge4.pdf');
    }

    public function pdfsemijudge5()
    {
        $data['title'] = 'Semis Judge 5';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdfsemisjudge5', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('semis_result_judge5.pdf');
    }

    public function top5()
    {

        $data['title'] = 'TOP 5';

        $data['final_rank'] = FinalRanking::where('semi_rank', '<=', 5)->get();

        return view('admin.reports.top5', compact('data'));
    }

    public function pdftop5()
    {
        $data['title'] = 'TOP 5';

        $data['final_rank'] = FinalRanking::where('semi_rank', '<=', 5)->inRandomOrder()->get();

        $pdf = PDF::loadView('admin.reports.pdftop5', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('top5_result.pdf');
    }

    public function putfinalscore()
    {

        $semifinal_scores = FinalScore::inRandomOrder()->get();

        foreach ($semifinal_scores as $scores) {

            $beauty = rand(19, 40);
            $intelligence = rand(9, 60);

            print_r("CANDIDATE: " . $scores->barangay_id . " --- BEAUTY -> " . $beauty . " || " . "INTELLIGENCE -> " . $intelligence . "<br>");

            $update_beauty = FinalScore::where('judge_id', $scores->judge_id)
                ->where("barangay_id", $scores->barangay_id)
                ->update(['beauty' => $beauty]);

            $update_intelligence = FinalScore::where('judge_id', $scores->judge_id)
                ->where("barangay_id", $scores->barangay_id)
                ->update(['intelligence' => $intelligence]);
        }
    }

    public function final()
    {
        $data['title'] = 'FINALS';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.final', compact('data'));
    }

    public function finalrank()
    {
        //ranking per judge
        for ($x = 2; $x <= 6; $x++) {
            $score = FinalScore::where('judge_id', $x)
                ->select(DB::raw('beauty +  intelligence as score'), 'barangay_id')
                ->orderBy('score', 'desc')
                ->get();

            $prev_rank = 1;
            $prev_score = 100;

            foreach ($score as $index => $score) {
                $rank = $index + 1;
                $brgy_id = $score->barangay_id;
                // print_r("Rank: " . $rank . ":" . $brgy_id . "(" . $score->score . ")<br>");

                if ($prev_score > $score->score) {

                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['final_rank' => $rank]);

                    $new_rank = $rank;
                } elseif ($prev_score == $score->score) {

                    $update_rank = Ranking::where('judge_id', $x)
                        ->where('barangay_id', $brgy_id)
                        ->update(['final_rank' => $prev_rank]);

                    $new_rank = $prev_rank;
                }

                $prev_rank = $new_rank;
                $prev_score = $score->score;
            }
        }

        //ranking for semis
        $get_rank = Ranking::whereNotNull('final_rank')->select(DB::raw('SUM(final_rank) as total'), 'barangay_id')
            ->groupBy('barangay_id')
            ->orderBy('total', 'asc')
            ->get();

        $prev_total_rank = 5;
        $prev_final_rank = 1;

        foreach ($get_rank as $idx => $final_rank) {
            $final_ranking = $idx + 1;

            if ($prev_total_rank < $final_rank->total) {

                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['final_rank' => $final_ranking]);

                $new_final_rank = $final_ranking;
            } elseif ($prev_total_rank == $final_rank->total) {

                $update_final_rank = FinalRanking::where('barangay_id', $final_rank->barangay_id)
                    ->update(['final_rank' => $prev_final_rank]);

                $new_final_rank = $prev_final_rank;
            }

            $prev_total_rank = $final_rank->total;
            $prev_final_rank = $new_final_rank;
        }
    }

    public function pdffinal()
    {
        $data['title'] = 'FINALS';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdffinal', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('finals_score.pdf');
    }

    public function finaljudge1()
    {
        $data['title'] = 'Final Judge 1';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.finaljudge1', compact('data'));
    }

    public function finaljudge2()
    {
        $data['title'] = 'Final Judge 2';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.finaljudge2', compact('data'));
    }

    public function finaljudge3()
    {
        $data['title'] = 'Final Judge 3';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.finaljudge3', compact('data'));
    }

    public function finaljudge4()
    {
        $data['title'] = 'Final Judge 4';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.finaljudge4', compact('data'));
    }

    public function finaljudge5()
    {
        $data['title'] = 'Final Judge 5';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        return view('admin.reports.finaljudge5', compact('data'));
    }

    public function pdffinaljudge1()
    {
        $data['title'] = 'Final Judge 1';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdffinaljudge1', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('finals_score_judge1.pdf');
    }

    public function pdffinaljudge2()
    {
        $data['title'] = 'Final Judge 2';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdffinaljudge2', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('finals_score_judge2.pdf');
    }

    public function pdffinaljudge3()
    {
        $data['title'] = 'Final Judge 3';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdffinaljudge3', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('finals_score_judge3.pdf');
    }

    public function pdffinaljudge4()
    {
        $data['title'] = 'Final Judge 4';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdffinaljudge4', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('finals_score_judge4.pdf');
    }

    public function pdffinaljudge5()
    {
        $data['title'] = 'Final Judge 5';

        $data['brgy'] = Barangay::all();

        $data['rank'] = Ranking::all();

        $pdf = PDF::loadView('admin.reports.pdffinaljudge5', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('finals_score_judge5.pdf');
    }

    public function finalresult()
    {

        $data['title'] = 'FINAL RESULT';

        $data['final_rank'] = FinalRanking::where('final_rank', '<=', 5)->orderBy('final_rank')->get();

        return view('admin.reports.finalresult', compact('data'));
    }

    public function pdffinalresult()
    {

        $data['title'] = 'FINAL RESULT';

        $data['final_rank'] = FinalRanking::where('final_rank', '<=', 5)->orderBy('final_rank')->get();

        $pdf = PDF::loadView('admin.reports.pdffinalresult', compact('data'))->setPaper('Legal', 'landscape');

        return $pdf->stream('finals_result.pdf');
    }
}
