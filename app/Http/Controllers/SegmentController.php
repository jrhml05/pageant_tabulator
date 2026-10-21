<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\OpenSegment;
use App\Models\ScoreLock;
use App\Models\User;
use App\Scoring\Segment;

class SegmentController extends Controller
{
    public function open(string $segment)
    {
        $segment = Segment::findOrFail($segment);
        OpenSegment::firstOrCreate(['segment' => $segment->key]);

        $message = "{$segment->short} is open. {$segment->panelLabel()} judges see it on their tablets within a few seconds.";
        if ($segment->forFinalistsOnly() && ! Candidate::where('is_finalist', true)->exists()) {
            session()->flash('error', "{$segment->short} is open, but no finalists are saved yet, so judges have no one to score. Save the finalists on each Round 1 results page.");
        } else {
            session()->flash('success', $message);
        }

        return redirect()->route('home');
    }

    public function close(string $segment)
    {
        $segment = Segment::findOrFail($segment);
        OpenSegment::whereKey($segment->key)->delete();

        session()->flash('success', "{$segment->short} is closed. Judges can no longer change its scores.");

        return redirect()->route('home');
    }

    public function unlock(string $segment, int $judge)
    {
        $segment = Segment::findOrFail($segment);
        $judge = User::findOrFail($judge);

        ScoreLock::where('segment', $segment->key)->where('judge_id', $judge->id)->delete();

        session()->flash('success', "{$judge->name}'s {$segment->short} sheet is unlocked. They can change scores while the segment is open, then lock in again.");

        return redirect()->route('home');
    }
}
