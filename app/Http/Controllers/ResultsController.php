<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Score;
use App\Models\ScoreLock;
use App\Models\User;
use App\Scoring\Announcement;
use App\Scoring\Segment;
use App\Scoring\Tabulator;
use App\Scoring\TieBreak;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ResultsController extends Controller
{
    // Long bond paper (8.5 x 13 in), landscape.
    private const PAPER = [0, 0, 612, 936];

    public function __construct(private Tabulator $tabulator) {}

    public function roundOne(string $division)
    {
        $this->rememberDivision($division);

        return view('admin.results.round1', $this->roundOneData($division));
    }

    public function roundOnePdf(string $division)
    {
        return Pdf::loadView('admin.results.pdf.round1', $this->roundOneData($division))
            ->setPaper(self::PAPER, 'landscape')
            ->stream("{$division}_round_1.pdf");
    }

    public function saveFinalists(Request $request, Announcement $announcement, string $division)
    {
        $validated = $request->validate([
            'finalists' => 'required|array|min:1',
            'finalists.*' => ['integer', Rule::exists('candidates', 'id')->where('division', $division)],
        ], [
            'finalists.required' => 'Tick at least one finalist.',
        ]);

        $before = Candidate::where('division', $division)->where('is_finalist', true)->get();

        DB::transaction(function () use ($division, $validated) {
            Candidate::where('division', $division)->whereNotIn('id', $validated['finalists'])
                ->update(['is_finalist' => false, 'announce_order' => null]);
            Candidate::whereIn('id', $validated['finalists'])->update(['is_finalist' => true]);
        });

        // A new set of finalists gets a new call order; the same set keeps the one already printed.
        $announcement->shuffle($division);

        $count = count($validated['finalists']);
        $expected = (int) config('pageant.finalists');
        $label = config("pageant.divisions.{$division}");
        $messages = ["{$count} {$label} finalists saved."
            .($count === $expected ? '' : " The usual number is {$expected}; change the ticks if that wasn't intended.")];

        $names = fn ($candidates) => $candidates->sortBy('number')->map(fn ($c) => "No. {$c->number}")->join(', ', ' and ');
        $later = Segment::all()->filter(fn (Segment $segment) => $segment->forFinalistsOnly());
        $scoredLater = fn ($candidates) => $candidates->filter(fn (Candidate $c) => Score::whereIn('segment', $later->keys())
            ->where('candidate_id', $c->id)->whereNotNull('points')->exists());

        foreach ($later as $segment) {
            $unscored = $segment->unscored()->get($division, collect());
            $locks = ScoreLock::where('segment', $segment->key);
            // A lock-in covers the finalists at the time, so judges are let back in to score the new ones.
            if ($unscored->isNotEmpty() && $locks->exists()) {
                $locks->delete();
                $messages[] = "{$segment->short} sheets are unlocked so the judges can score {$names($unscored)}"
                    .($segment->isOpen() ? '' : " once you open {$segment->short}").'. They lock in again when done.';
            } elseif ($unscored->isNotEmpty() && Score::where('segment', $segment->key)->whereNotNull('points')->exists()) {
                $messages[] = "{$names($unscored)} still need {$segment->short} scores.";
            }
        }

        // Kept, so ticking them again brings their scores back.
        $dropped = $scoredLater($before->whereNotIn('id', $validated['finalists']));
        if ($dropped->isNotEmpty()) {
            $one = $dropped->count() === 1;
            $messages[] = "{$names($dropped)} ".($one ? 'has' : 'have').' Round 2 scores that no longer count. '
                .'They are kept and count again if you tick '.($one ? 'that candidate' : 'them').' again.';
        }

        session()->flash('success', implode(' ', $messages));

        return redirect()->route('results.round1', $division);
    }

    public function shuffleAnnouncement(Announcement $announcement, string $division)
    {
        $announcement->shuffle($division, force: true);

        session()->flash('success', 'New call order drawn for the '.config("pageant.divisions.{$division}").' top 5. Print the announcement sheet again.');

        return redirect()->route('results.round1', $division);
    }

    /** Both divisions' finalists in their random call order, for the emcee. No ranks or scores. */
    public function announcementPdf()
    {
        $divisions = collect(config('pageant.divisions'))->map(fn ($label, $division) => Announcement::order($division));

        return Pdf::loadView('admin.results.pdf.announcement', ['divisions' => $divisions])
            ->setPaper(self::PAPER, 'landscape')
            ->stream('top_5_announcement.pdf');
    }

    public function segment(Request $request, string $division, string $segment)
    {
        $this->rememberDivision($division);

        return view('admin.results.segment', $this->segmentData($request, $division, $segment));
    }

    public function segmentPdf(Request $request, string $division, string $segment)
    {
        $data = $this->segmentData($request, $division, $segment);
        $name = $division.'_'.$segment.($data['judge'] ? "_judge{$data['seat']}" : '');

        return Pdf::loadView('admin.results.pdf.segment', $data)
            ->setPaper(self::PAPER, 'landscape')
            ->stream("{$name}.pdf");
    }

    /** Round 2: the announcement of winners, 4th runner-up first and the winner last. Any other segment: its winner. */
    public function segmentWinnersPdf(string $division, string $segment)
    {
        $result = $this->tabulator->segment(Segment::findOrFail($segment), $division);
        $name = $result['segment']->forFinalistsOnly() ? 'winners' : 'winner';

        return Pdf::loadView('admin.results.pdf.winners', [
            'division' => $division,
            'label' => config("pageant.divisions.{$division}"),
            'result' => $result,
            'winners' => Tabulator::winners($result),
        ])
            ->setPaper(self::PAPER, 'landscape')
            ->stream("{$division}_{$segment}_{$name}.pdf");
    }

    /**
     * Saves the board's order for tied candidates, or clears one (`clear` = its rank). Only once
     * every judge has locked in, so the averages can't move under the decision.
     */
    public function settleTies(Request $request, string $division, string $segment)
    {
        $segment = Segment::findOrFail($segment);
        $result = $this->tabulator->segment($segment, $division);
        $back = redirect()->route('results.segment', [$division, $segment->key]);
        $names = fn (array $tie) => $tie['candidates']->map(fn (Candidate $c) => "No. {$c->number}")->join(', ', ' and ');

        if (! Tabulator::isFinal($result)) {
            return $back->with('error', 'A tie can be settled once every judge has locked in and scored every '
                .($segment->forFinalistsOnly() ? 'finalist' : 'candidate').'.');
        }

        $ties = collect($result['ties'])->keyBy('rank');
        $changed = 'The ties changed while this page was open. Check the results and choose again.';

        if ($request->filled('clear')) {
            $tie = $ties->get($request->integer('clear'));
            if (! $tie) {
                return $back->with('error', $changed);
            }
            TieBreak::clear($segment, $division, $tie['rank']);

            return $back->with('success', "Cleared. {$names($tie)} are tied for ".TieBreak::place($segment, $tie['rank'], $division).' again.');
        }

        $picks = collect($request->input('ties', []))->map(fn ($order) => array_map('intval', array_values((array) $order)));
        if ($picks->keys()->diff($ties->keys())->isNotEmpty()) {
            return $back->with('error', $changed);
        }
        if ($picks->isEmpty()) {
            return $back->with('error', $segment->forFinalistsOnly() ? 'Choose who places higher first.' : 'Choose the winner first.');
        }

        $orders = [];
        foreach ($picks as $rank => $order) {
            $tie = $ties[$rank];
            $ids = $tie['candidates']->pluck('id')->all();
            $count = TieBreak::picks($segment, count($ids));
            if (count($order) !== $count || count(array_unique($order)) !== $count || array_diff($order, $ids)) {
                return $back->with('error', 'In the tie for '.TieBreak::place($segment, $rank, $division)
                    .', choose a different candidate for each place.');
            }
            // Round 2 orders the whole tie: the one left over takes the last place.
            $orders[$rank] = $segment->forFinalistsOnly() ? [...$order, ...array_diff($ids, $order)] : $order;
        }

        DB::transaction(function () use ($segment, $division, $ties, $orders) {
            foreach ($orders as $rank => $order) {
                TieBreak::save($segment, $division, $ties[$rank], $order);
            }
        });

        return $back->with('success', 'Saved. '.collect($orders)
            ->map(fn ($order, $rank) => TieBreak::note($segment, ['order' => $order] + $ties[$rank]))->join(' '));
    }

    /** The sidebar's results links open this division next. */
    private function rememberDivision(string $division): void
    {
        session()->put('results.division', $division);
    }

    private function roundOneData(string $division): array
    {
        return [
            'division' => $division,
            'label' => config("pageant.divisions.{$division}"),
            'result' => $this->tabulator->roundOne($division),
            'finalists' => Candidate::where('division', $division)->where('is_finalist', true)->pluck('id'),
            'announcement' => Announcement::order($division),
            // Round 2 already has scores or lock-ins, so changing the finalists needs a confirm.
            'laterStarted' => Segment::all()->filter(fn (Segment $segment) => $segment->forFinalistsOnly())
                ->contains(fn (Segment $segment) => Score::where('segment', $segment->key)->whereNotNull('points')->exists()
                    || ScoreLock::where('segment', $segment->key)->exists()),
            'judges' => Segment::inRound(1)->map->panel->unique()
                ->flatMap(fn (string $panel) => User::onPanel($panel)->get()),
        ];
    }

    private function segmentData(Request $request, string $division, string $segment): array
    {
        $result = $this->tabulator->segment(Segment::findOrFail($segment), $division);

        $seat = $request->integer('judge') ?: null;
        $judge = $seat ? $result['judges']->get($seat - 1) : null;
        abort_if($seat && ! $judge, 404);

        return [
            'division' => $division,
            'label' => config("pageant.divisions.{$division}"),
            'result' => $result,
            'seat' => $seat,
            'judge' => $judge,
        ];
    }
}
