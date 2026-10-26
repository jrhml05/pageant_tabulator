<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\OpenSegment;
use App\Models\Score;
use App\Models\ScoreLock;
use App\Scoring\ScoreReset;
use App\Scoring\Segment;

class ScoreResetController extends Controller
{
    public function index()
    {
        // Saved entries (not blanks) per segment and criterion, and lock-ins per segment.
        $entries = Score::whereNotNull('points')->selectRaw('segment, criterion, count(*) as total')
            ->groupBy('segment', 'criterion')->get()->groupBy('segment')
            ->map(fn ($rows) => $rows->pluck('total', 'criterion')->map(fn ($n) => (int) $n));
        $locks = ScoreLock::selectRaw('segment, count(*) as total')->groupBy('segment')->pluck('total', 'segment');

        $group = fn (string $label, $segments) => [
            'label' => $label,
            'segments' => $segments->map(fn (Segment $segment) => [
                'segment' => $segment,
                'criteria' => collect($segment->criteria)->map(fn ($rule, $key) => (int) ($entries[$segment->key][$key] ?? 0)),
                'entries' => (int) ($entries[$segment->key] ?? collect())->sum(),
                'locks' => (int) ($locks[$segment->key] ?? 0),
            ]),
        ];

        // Each segment's own reset button covers the awards, so they get no group-wide one.
        $groups = collect(config('pageant.rounds'))
            ->map(fn (string $label, int $round) => $group($label, Segment::inRound($round)) + ['round' => $round])
            ->when(Segment::awards()->isNotEmpty(), fn ($all) => $all->put('awards', $group('Special awards', Segment::awards()) + ['round' => null]))
            ->map(fn (array $group) => $group + [
                'entries' => $group['segments']->sum('entries'),
                'locks' => $group['segments']->sum('locks'),
            ]);

        return view('admin.reset', [
            'groups' => $groups,
            'anything' => $groups->sum('entries') + $groups->sum('locks') > 0
                || OpenSegment::exists() || Candidate::where('is_finalist', true)->exists(),
        ]);
    }

    public function criterion(string $segment, string $criterion)
    {
        $segment = Segment::findOrFail($segment);
        $label = $segment->criteria[$criterion]['label'] ?? abort(404);

        ScoreReset::criterion($segment, $criterion);

        return $this->done("{$segment->short}: {$label} scores are deleted, and so are the segment's lock-ins, so judges can score it again while it is open.");
    }

    public function segment(string $segment)
    {
        $segment = Segment::findOrFail($segment);

        ScoreReset::segment($segment);

        return $this->done("{$segment->short} scores and lock-ins are deleted.");
    }

    public function round(int $round)
    {
        $label = config("pageant.rounds.{$round}") ?? abort(404);

        ScoreReset::round($round);

        $note = $round === 1 ? ' Saved finalists stay; change them on each Round 1 results page.' : '';

        return $this->done("{$label} scores and lock-ins are deleted.{$note}");
    }

    public function everything()
    {
        ScoreReset::everything();

        return $this->done('Every score, lock-in and finalist is cleared, and every segment is closed.');
    }

    private function done(string $message)
    {
        session()->flash('success', $message);

        return redirect()->route('reset.index');
    }
}
