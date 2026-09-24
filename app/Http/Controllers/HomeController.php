<?php

namespace App\Http\Controllers;

use App\Models\Mr_candidate;
use App\Models\Ms_candidate;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Category score tables per stage id, as [label, table suffix, report route suffix]. Each Mr/Ms
     * pair shares both suffixes: `mr_talent_scores` / `mr_talent`, `ms_talent_scores` / `ms_talent`.
     */
    private const CATEGORIES = [
        1 => [['Rave wear', 'ravewear_scores', 'rave_wear'], ['Talent', 'talent_scores', 'talent']],
        2 => [
            ['National costume', 'natlcost_scores', 'national_costume'],
            ['Departmental uniform', 'deptuni_scores', 'departmental_uniform'],
            ['Swim wear', 'swimwear_scores', 'swim_wear'],
            ['Formal wear', 'formalwear_scores', 'formal_wear'],
            ['Casual Q&A', 'qna_scores', 'qna'],
        ],
        3 => [['Final', 'final_scores', 'final']],
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $judges = User::where('role', 'judge')->orderBy('id')->get(['id', 'name']);

        $stages = Stage::orderBy('id')->get()->map(fn (Stage $stage) => [
            'name' => $stage->stage_name,
            'active' => (bool) $stage->is_active,
            'categories' => collect(self::CATEGORIES[$stage->id] ?? [])->map(function ($category) use ($judges) {
                [$label, $suffix, $report] = $category;

                $locked = fn (string $division) => DB::table("{$division}_{$suffix}")
                    ->where('is_lock', 1)
                    ->distinct()
                    ->pluck('judge_id');

                return [
                    'label' => $label,
                    'report' => $report,
                    'mr' => $judges->whereIn('id', $locked('mr')),
                    'ms' => $judges->whereIn('id', $locked('ms')),
                ];
            }),
        ]);

        $data['title'] = 'Dashboard';
        $data['mr_count'] = Mr_candidate::count();
        $data['ms_count'] = Ms_candidate::count();
        $data['judges'] = $judges;
        $data['stages'] = $stages;

        return view('home', compact('data'));
    }
}
