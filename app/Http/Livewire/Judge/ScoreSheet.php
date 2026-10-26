<?php

namespace App\Http\Livewire\Judge;

use App\Models\Candidate;
use App\Models\Score;
use App\Models\ScoreLock;
use App\Scoring\Points;
use App\Scoring\ScoreReset;
use App\Scoring\Segment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

/**
 * A judge's sheet for one segment: every Ms. and Mr. candidate, paired by number.
 * Entries are saved as they are typed; invalid ones are saved as blank so results never use them.
 * A ranked segment has no inputs: the judge taps a rank on each card (rank()).
 */
class ScoreSheet extends Component
{
    #[Locked]
    public string $segmentKey;

    /** Entries as typed, keyed "c{candidate id}" then criterion ("c" keeps Livewire from treating it as a list). */
    public array $points = [];

    /** Why the last change or lock-in was refused, shown above the sheet. */
    public ?string $notice = null;

    /** After a rank was taken from one candidate and given to another: which, so the judge re-ranks the first. */
    public ?string $moved = null;

    /** "pairs" (Ms. and Mr. side by side) or one division. Kept in the session so it carries to the next segment. */
    #[Locked]
    public string $viewMode = 'pairs';

    /** The segment's reset mark when the sheet loaded (see ScoreReset). */
    #[Locked]
    public ?string $resetMark = null;

    private const VIEW_SESSION_KEY = 'judge.view_mode';

    public function mount(string $segment): void
    {
        $this->segmentKey = $segment;
        $this->points = $this->savedPoints();
        $this->resetMark = ScoreReset::mark($segment);
        $this->viewMode = in_array(session(self::VIEW_SESSION_KEY), self::viewModes(), true) ? session(self::VIEW_SESSION_KEY) : 'pairs';
    }

    public function showView(string $mode): void
    {
        if (! in_array($mode, self::viewModes(), true)) {
            return;
        }

        $this->viewMode = $mode;
        session()->put(self::VIEW_SESSION_KEY, $mode);
    }

    /** @return list<string> */
    public static function viewModes(): array
    {
        return ['pairs', ...array_keys(config('pageant.divisions'))];
    }

    #[Computed]
    public function segment(): Segment
    {
        return Segment::findOrFail($this->segmentKey);
    }

    /** @return Collection<string, Collection<int, Candidate>> by division */
    #[Computed]
    public function candidates(): Collection
    {
        return collect(config('pageant.divisions'))->map(fn ($label, $division) => $this->segment->candidates($division));
    }

    #[Computed]
    public function locked(): bool
    {
        return ScoreLock::where('segment', $this->segmentKey)->where('judge_id', Auth::id())->exists();
    }

    /**
     * The browser may send one entry ("c3.skill") or a whole candidate ("c3"), so the full sheet
     * is compared with what is saved and only the entries that differ are written.
     */
    public function updatedPoints(): void
    {
        if ($this->reloadAfterReset() || $this->refused()) {
            return;
        }

        // Ranks are only given through rank(), which keeps each rank on one candidate.
        if ($this->segment->isRanked()) {
            $this->points = $this->savedPoints();

            return;
        }

        $saved = Score::where('segment', $this->segmentKey)->where('judge_id', Auth::id())->get()
            ->keyBy(fn (Score $score) => "{$score->candidate_id}.{$score->criterion}");

        foreach ($this->candidateIds() as $candidateId) {
            foreach ($this->segment->criteria as $criterion => $rule) {
                $entry = $this->points["c{$candidateId}"][$criterion] ?? '';
                $parsed = Points::parse(is_scalar($entry) ? $entry : '', $rule['max']);
                $points = $parsed === false ? null : $parsed;
                $current = $saved->get("{$candidateId}.{$criterion}")?->points;

                if ($points === null ? $current === null : ($current !== null && Points::hundredths($current) === Points::hundredths($points))) {
                    continue;
                }

                Score::updateOrCreate(
                    ['segment' => $this->segmentKey, 'candidate_id' => $candidateId, 'judge_id' => Auth::id(), 'criterion' => $criterion],
                    ['points' => $points],
                );
            }
        }

        $this->notice = null;
    }

    /**
     * Gives a candidate a rank in their division. A rank another candidate holds moves: that
     * candidate is left without one, and `moved` says so. Tapping the rank a candidate already
     * has changes nothing, so a double tap never clears it.
     */
    public function rank(int $candidateId, int $rank): void
    {
        if ($this->reloadAfterReset() || $this->refused() || ! $this->segment->isRanked()) {
            return;
        }

        $candidate = $this->candidates->flatten(1)->firstWhere('id', $candidateId);
        if (! $candidate || $rank < 1 || $rank > $this->candidates[$candidate->division]->count()) {
            return;
        }

        $saved = Score::where('segment', $this->segmentKey)->where('judge_id', Auth::id())
            ->where('criterion', Segment::RANK)->whereNotNull('points')
            ->whereIn('candidate_id', $this->candidates[$candidate->division]->pluck('id'))
            ->pluck('points', 'candidate_id')->map(fn ($points) => (int) $points);

        if ($saved->get($candidate->id) === $rank) {
            return;
        }

        $this->moved = null;
        foreach ($saved->filter(fn ($held, $id) => $held === $rank && $id !== $candidate->id)->keys() as $holderId) {
            $this->saveRank($holderId, null);
            $holder = $this->candidates[$candidate->division]->firstWhere('id', $holderId);
            $this->moved = "Rank {$rank} moved from {$holder->shortName()} to {$candidate->shortName()}. Give {$holder->shortName()} a rank again.";
        }

        $this->saveRank($candidate->id, $rank);
        $this->notice = null;
    }

    /**
     * Clears this judge's ranks in the divisions on screen (both when side by side), so they can
     * rank again from scratch. Other judges' ranks are left alone.
     */
    public function clearRanking(): void
    {
        if ($this->reloadAfterReset() || $this->refused() || ! $this->segment->isRanked()) {
            return;
        }

        $candidates = ($this->viewMode === 'pairs' ? $this->candidates : $this->candidates->only($this->viewMode))->flatten(1);
        Score::where('segment', $this->segmentKey)->where('judge_id', Auth::id())
            ->whereIn('candidate_id', $candidates->pluck('id'))->delete();

        foreach ($candidates as $candidate) {
            $this->points["c{$candidate->id}"][Segment::RANK] = '';
        }
        $this->moved = null;
        $this->notice = null;
    }

    public function lock(): void
    {
        if ($this->reloadAfterReset() || $this->locked) {
            return;
        }

        if (! $this->isScorable()) {
            $this->notice = "The tabulator has closed {$this->segment->short}, so it can't be locked in.";

            return;
        }

        // Checked against the saved scores, not only what is on screen.
        $expected = $this->candidateIds()->count() * count($this->segment->criteria);
        $saved = Score::where('segment', $this->segmentKey)->where('judge_id', Auth::id())
            ->whereIn('candidate_id', $this->candidateIds())
            ->whereIn('criterion', array_keys($this->segment->criteria))
            ->whereNotNull('points')
            ->count();

        if ($expected === 0 || $saved < $expected || $this->progress()['invalid'] > 0 || ! $this->savedRanksAreValid()) {
            $this->notice = $this->segment->isRanked()
                ? 'Some candidates have no rank or share one. Fix the cards marked below, then lock in again.'
                : 'Some scores are missing or out of range. Fix the cards marked below, then lock in again.';

            return;
        }

        ScoreLock::firstOrCreate(['segment' => $this->segmentKey, 'judge_id' => Auth::id()]);
        unset($this->locked);
        $this->notice = null;
    }

    /**
     * Per-candidate state from what is typed: entries filled, invalid entries, and the total.
     * In a ranked segment the total is the rank, and a rank out of range or shared is invalid.
     *
     * @return array<int, array{filled: int, invalid: int, total: ?float, complete: bool}>
     */
    public function cardStates(): array
    {
        if ($this->segment->isRanked()) {
            return $this->rankStates();
        }

        $criteria = $this->segment->criteria;
        $states = [];

        foreach ($this->candidates->flatten(1) as $candidate) {
            $filled = $invalid = 0;
            $total = 0.0;
            foreach ($criteria as $key => $criterion) {
                $parsed = Points::parse($this->points["c{$candidate->id}"][$key] ?? '', $criterion['max']);
                if ($parsed === false) {
                    $invalid++;
                } elseif ($parsed !== null) {
                    $filled++;
                    $total += $parsed;
                }
            }
            $states[$candidate->id] = [
                'filled' => $filled,
                'invalid' => $invalid,
                'total' => $filled ? $total : null,
                'complete' => $filled === count($criteria),
            ];
        }

        return $states;
    }

    /** @return array<int, array{filled: int, invalid: int, total: ?float, complete: bool}> */
    private function rankStates(): array
    {
        $states = [];
        foreach ($this->candidates as $candidates) {
            $ranks = $candidates->mapWithKeys(fn ($c) => [$c->id => $this->points["c{$c->id}"][Segment::RANK] ?? ''])
                ->map(fn ($entry) => ctype_digit((string) $entry) ? (int) $entry : null);
            $counts = $ranks->filter()->countBy();

            foreach ($ranks as $id => $rank) {
                $invalid = $rank !== null && ($rank < 1 || $rank > $candidates->count() || $counts[$rank] > 1);
                $states[$id] = [
                    'filled' => (int) ($rank !== null),
                    'invalid' => (int) $invalid,
                    'total' => $rank === null ? null : (float) $rank,
                    'complete' => $rank !== null && ! $invalid,
                ];
            }
        }

        return $states;
    }

    /** @return array{scored: int, total: int, invalid: int} candidates fully scored, and invalid entries */
    public function progress(): array
    {
        $states = collect($this->cardStates());

        return [
            'scored' => $states->where('complete', true)->count(),
            'total' => $states->count(),
            'invalid' => $states->sum('invalid'),
        ];
    }

    /**
     * Rows of the sheet. Round 1 pairs Ms. and Mr. candidates by number; later rounds line the
     * finalists up side by side in number order, since finalists' numbers rarely match.
     *
     * @return list<array{number: ?int, ms: ?Candidate, mr: ?Candidate}>
     */
    public function rows(): array
    {
        $ms = $this->candidates['ms']->values();
        $mr = $this->candidates['mr']->values();

        if ($this->segment->forFinalistsOnly()) {
            return collect(range(0, max($ms->count(), $mr->count(), 1) - 1))
                ->map(fn ($i) => ['number' => null, 'ms' => $ms->get($i), 'mr' => $mr->get($i)])
                ->reject(fn ($row) => ! $row['ms'] && ! $row['mr'])
                ->values()->all();
        }

        return $ms->pluck('number')->merge($mr->pluck('number'))->unique()->sort()
            ->map(fn ($n) => ['number' => $n, 'ms' => $ms->firstWhere('number', $n), 'mr' => $mr->firstWhere('number', $n)])
            ->values()->all();
    }

    public function render()
    {
        $states = $this->cardStates();
        $visible = $this->viewMode === 'pairs' ? $this->candidates->flatten(1) : $this->candidates[$this->viewMode];
        $rest = $this->candidates->except($this->viewMode === 'pairs' ? [] : [$this->viewMode])
            ->map(fn ($candidates) => $candidates->reject(fn ($c) => $states[$c->id]['complete'])->count())
            ->filter();

        return view('livewire.judge.score-sheet', [
            'rows' => $this->rows(),
            'visible' => $visible,
            'states' => $states,
            'progress' => $this->progress(),
            'firstUnscored' => $visible->first(fn ($c) => ! $states[$c->id]['complete']),
            // Divisions out of view that still need scores, for the "go to" button: [division => count].
            'unscoredElsewhere' => $this->viewMode === 'pairs' ? collect() : $rest,
        ]);
    }

    /**
     * After the tabulator resets the segment, the entries on screen are stale: saving the sheet
     * would put the deleted scores back. Reload what is saved instead.
     */
    private function reloadAfterReset(): bool
    {
        $mark = ScoreReset::mark($this->segmentKey);
        if ($mark === $this->resetMark) {
            return false;
        }

        $this->resetMark = $mark;
        $this->points = $this->savedPoints();
        $this->moved = null;
        unset($this->locked);
        $this->notice = "The tabulator reset scores in {$this->segment->short}. Your sheet now shows what is saved; enter that change again.";

        return true;
    }

    /** Outside a ranked segment, always. In one, each division's saved ranks run 1 to its candidate count, once each. */
    private function savedRanksAreValid(): bool
    {
        if (! $this->segment->isRanked()) {
            return true;
        }

        $saved = Score::where('segment', $this->segmentKey)->where('judge_id', Auth::id())
            ->where('criterion', Segment::RANK)->whereNotNull('points')->pluck('points', 'candidate_id');

        return $this->candidates->every(function ($candidates) use ($saved) {
            $ranks = $candidates->map(fn ($c) => (int) ($saved[$c->id] ?? 0))->sort()->values()->all();

            return $candidates->isEmpty() || $ranks === range(1, $candidates->count());
        });
    }

    /** Refuses a change when the sheet is locked or the segment closed, and puts the saved entries back. */
    private function refused(): bool
    {
        if (! $this->locked && $this->isScorable()) {
            return false;
        }

        $this->points = $this->savedPoints();
        $this->notice = $this->locked
            ? 'Your scores for this segment are locked in, so that change was not saved.'
            : "The tabulator has closed {$this->segment->short}, so that change was not saved.";

        return true;
    }

    private function saveRank(int $candidateId, ?int $rank): void
    {
        Score::updateOrCreate(
            ['segment' => $this->segmentKey, 'candidate_id' => $candidateId, 'judge_id' => Auth::id(), 'criterion' => Segment::RANK],
            ['points' => $rank],
        );
        $this->points["c{$candidateId}"][Segment::RANK] = $rank === null ? '' : (string) $rank;
    }

    /** Open, and scored by this judge's panel. */
    private function isScorable(): bool
    {
        return $this->segment->panel === Auth::user()->panel && $this->segment->isOpen();
    }

    private function candidateIds(): Collection
    {
        return $this->candidates->flatten(1)->pluck('id');
    }

    private function savedPoints(): array
    {
        $saved = Score::where('segment', $this->segmentKey)->where('judge_id', Auth::id())->get()
            ->groupBy('candidate_id');

        $points = [];
        foreach ($this->candidates->flatten(1) as $candidate) {
            foreach (array_keys($this->segment->criteria) as $criterion) {
                $value = $saved->get($candidate->id)?->firstWhere('criterion', $criterion)?->points;
                $points["c{$candidate->id}"][$criterion] = $value === null ? '' : Points::plain($value);
            }
        }

        return $points;
    }
}
