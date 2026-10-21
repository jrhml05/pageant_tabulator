<?php

namespace App\Scoring;

use App\Models\Candidate;
use App\Models\OpenSegment;
use App\Models\Score;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * One scored segment (Talent, Swim wear, Round 2...), read from config/pageant.php.
 */
final class Segment
{
    /**
     * @param  array<string, array{label: string, max: float}>  $criteria
     */
    private function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $short,
        public readonly int $round,
        public readonly string $panel,
        public readonly array $criteria,
        public readonly string $ranking = 'rank_sum',
    ) {}

    /** @return Collection<string, self> */
    public static function all(): Collection
    {
        return collect(config('pageant.segments'))->map(fn (array $segment, string $key) => new self(
            $key,
            $segment['label'],
            $segment['short'] ?? $segment['label'],
            $segment['round'],
            $segment['panel'],
            collect($segment['criteria'])->map(fn (array $c) => ['label' => $c[0], 'max' => (float) $c[1]])->all(),
            $segment['ranking'] ?? 'rank_sum',
        ));
    }

    public static function find(?string $key): ?self
    {
        return $key === null ? null : self::all()->get($key);
    }

    public static function findOrFail(string $key): self
    {
        return self::find($key) ?? abort(404);
    }

    /** @return Collection<string, self> */
    public static function inRound(int $round): Collection
    {
        return self::all()->where('round', $round);
    }

    /** Open segments in config order, optionally only those a panel scores. */
    public static function open(?string $panel = null): Collection
    {
        $open = OpenSegment::pluck('segment');

        return self::all()
            ->filter(fn (self $segment) => $open->contains($segment->key))
            ->when($panel, fn (Collection $segments) => $segments->where('panel', $panel));
    }

    public function isOpen(): bool
    {
        return OpenSegment::whereKey($this->key)->exists();
    }

    public function maxPoints(): float
    {
        return array_sum(array_column($this->criteria, 'max'));
    }

    public function panelLabel(): string
    {
        return config("pageant.panels.{$this->panel}");
    }

    public function roundLabel(): string
    {
        return config("pageant.rounds.{$this->round}");
    }

    /** Placed by the sum of each judge's ranks (lowest wins) rather than by average score. */
    public function ranksBySum(): bool
    {
        return $this->ranking === 'rank_sum';
    }

    /** Round 1 shows the rank sum averaged over the judges, the way it counts toward the round. */
    public function showsAverageRank(): bool
    {
        return $this->ranksBySum() && $this->round === 1;
    }

    /** Only Round 1's finalists go on to be scored in later rounds. */
    public function forFinalistsOnly(): bool
    {
        return $this->round > 1;
    }

    /** @return Collection<int, User> */
    public function judges(): Collection
    {
        return User::onPanel($this->panel)->get();
    }

    /**
     * Candidates some judge on the panel hasn't scored on every criterion, by division (only
     * divisions with any). A lock-in doesn't cover a finalist saved after it, so this is how a
     * stale lock shows.
     *
     * @return Collection<string, Collection<int, Candidate>>
     */
    public function unscored(): Collection
    {
        $judges = $this->judges()->pluck('id');
        $needed = $judges->count() * count($this->criteria);
        $scored = Score::where('segment', $this->key)->whereIn('judge_id', $judges)
            ->whereIn('criterion', array_keys($this->criteria))->whereNotNull('points')
            ->selectRaw('candidate_id, count(*) as entries')->groupBy('candidate_id')
            ->pluck('entries', 'candidate_id');

        return collect(config('pageant.divisions'))
            ->map(fn ($label, string $division) => $this->candidates($division)
                ->filter(fn (Candidate $candidate) => ($scored[$candidate->id] ?? 0) < $needed)->values())
            ->filter(fn (Collection $candidates) => $candidates->isNotEmpty());
    }

    /** @return Collection<int, Candidate> */
    public function candidates(string $division): Collection
    {
        return Candidate::division($division)
            ->when($this->forFinalistsOnly(), fn ($query) => $query->where('is_finalist', true))
            ->get();
    }
}
