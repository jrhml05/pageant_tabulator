<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\ScoreLock;
use App\Models\User;
use App\Scoring\Segment;

class HomeController extends Controller
{
    public function index()
    {
        $judges = User::where('role', 'judge')->orderBy('id')->get();
        $locks = ScoreLock::all()->groupBy('segment');
        $open = Segment::open()->keys();

        // Grouped the way the night runs: pre-pageant, then Round 1, Round 2 and the separate awards on pageant night.
        $groups = Segment::all()->groupBy(fn (Segment $segment) => $segment->panel === 'prepageant'
            ? $segment->panelLabel()
            : "{$segment->panelLabel()}, {$segment->roundLabel()}")
            ->map(fn ($segments) => $segments->map(function (Segment $segment) use ($judges, $locks, $open) {
                $panel = $judges->where('panel', $segment->panel)->values();
                $locked = ($locks[$segment->key] ?? collect())->pluck('judge_id');
                $allLocked = $panel->isNotEmpty() && $panel->every(fn ($judge) => $locked->contains($judge->id));

                return [
                    'segment' => $segment,
                    'open' => $open->contains($segment->key),
                    'judges' => $panel,
                    'locked' => $locked,
                    // Locked in, yet someone (a finalist saved after the lock-in) isn't fully scored.
                    'unscored' => $allLocked ? $segment->unscored() : collect(),
                ];
            }));

        $data['title'] = 'Scoring control';
        $data['groups'] = $groups;
        $data['judges'] = $judges;
        $data['unassigned'] = $judges->whereNull('panel');
        $data['counts'] = Candidate::selectRaw('division, count(*) as total, sum(is_finalist) as finalists')
            ->groupBy('division')->get()->keyBy('division');

        return view('home', compact('data'));
    }
}
