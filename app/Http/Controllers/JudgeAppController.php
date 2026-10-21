<?php

namespace App\Http\Controllers;

use App\Models\ScoreLock;
use App\Scoring\Segment;
use Illuminate\Http\Request;

class JudgeAppController extends Controller
{
    /** Straight to the first open segment this judge hasn't locked; a waiting screen when none is open. */
    public function index(Request $request)
    {
        $judge = $request->user();
        $open = $judge->panel ? Segment::open($judge->panel) : collect();
        $locked = ScoreLock::where('judge_id', $judge->id)->pluck('segment');

        if ($open->isNotEmpty()) {
            $next = $open->first(fn (Segment $segment) => ! $locked->contains($segment->key)) ?? $open->first();

            return redirect()->route('judge.sheet', $next->key);
        }

        return view('judge_app.index', [
            'judge' => $judge,
            'segments' => Segment::all()->where('panel', $judge->panel),
            'locked' => $locked,
        ]);
    }

    /** Polled by the judge pages so a tablet notices when the tabulator opens or closes a segment. */
    public function status(Request $request)
    {
        $panel = $request->user()->panel;

        return response()->json([
            'open' => $panel ? Segment::open($panel)->pluck('short')->values()->all() : [],
        ]);
    }

    public function show(Request $request, string $segment)
    {
        $judge = $request->user();
        $segment = Segment::findOrFail($segment);

        if ($segment->panel !== $judge->panel || ! $segment->isOpen()) {
            session()->flash('error', "{$segment->short} isn't open for your panel right now.");

            return redirect()->route('judge.app');
        }

        return view('judge_app.sheet', [
            'segment' => $segment,
            'open' => Segment::open($judge->panel),
            'locked' => ScoreLock::where('judge_id', $judge->id)->pluck('segment'),
        ]);
    }
}
