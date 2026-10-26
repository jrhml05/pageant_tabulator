<?php

namespace App\Scoring;

use App\Models\Candidate;
use App\Models\OpenSegment;
use App\Models\Score;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * One scored segment (Talent, Swim wear, Round 2...), read from config/pageant.php. A segment
 * with no round is a separate award (Casual wear), placed on its own. A ranked segment is ranked
 * by each judge instead of scored, and its one criterion is `rank`.
 */
final class Segment
{
    public const RANK = 'rank';

    /**
     * @param  array<string, array{label: string, max: float}>  $criteria
     */
    private function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $short,
        public readonly ?int $round,
        public readonly string $panel,
        public readonly array $criteria,
        private readonly ?float $weight = null,
        private readonly bool $ranked = false,
    ) {}

    /** @return Collection<string, self> */
    public static function all(): Collection
    {
        return collect(config('pageant.segments'))->map(fn (array $segment, string $key) => new self(
            $key,
            $segment['label'],
            $segment['short'] ?? $segment['label'],
            $segment['round'] ?? null,
            $segment['panel'],
            // A rank's maximum is the number of candidates in the division (rankLimit()), not a fixed figure.
            ($segment['ranked'] ?? false)
                ? [self::RANK => ['label' => 'Rank', 'max' => 0.0]]
                : collect($segment['criteria'])->map(fn (array $c) => ['label' => $c[0], 'max' => (float) $c[1]])->all(),
            isset($segment['weight']) ? (float) $segment['weight'] : null,
            $segment['ranked'] ?? false,
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

    /** @return Collection<string, self> */
    public static function awards(): Collection
    {
        return self::all()->filter(fn (self $segment) => $segment->isAward());
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

    /** What the segment is worth in its round: its points, unless the config gives a `weight`. */
    public function weight(): float
    {
        return $this->weight ?? $this->maxPoints();
    }

    /** Judged out of its own points (Talent: 100) and scaled to its weight in the round. */
    public function isScaled(): bool
    {
        return $this->weight() != $this->maxPoints();
    }

    /**
     * Turns a judge's total in hundredths into ten-thousandths of the segment's weight: 100 for
     * an unscaled segment, 15 for Talent (90.00 points is 9000 → 135000, 13.5 of its 15).
     */
    public function scale(): int
    {
        return intdiv(Points::hundredths($this->weight()) * 100, Points::hundredths($this->maxPoints()));
    }

    /** A total or average in hundredths, at the segment's weight: Talent's 87.33 is 13.10 of its 15. */
    public function weighted(?int $hundredths): ?int
    {
        return $hundredths === null ? null : (int) round($hundredths * $this->scale() / 100);
    }

    public function panelLabel(): string
    {
        return config("pageant.panels.{$this->panel}");
    }

    public function roundLabel(): string
    {
        return $this->isAward() ? 'Special award' : config("pageant.rounds.{$this->round}");
    }

    /** Scored and placed on its own, outside Round 1 and Round 2. */
    public function isAward(): bool
    {
        return $this->round === null;
    }

    /** Ranked 1, 2, 3... by each judge instead of scored; the lowest rank total wins. */
    public function isRanked(): bool
    {
        return $this->ranked;
    }

    /** The lowest rank a judge can give in a division: one rank per candidate. */
    public function rankLimit(string $division): int
    {
        return $this->candidates($division)->count();
    }

    /** Only Round 1's finalists go on to be scored in later rounds. */
    public function forFinalistsOnly(): bool
    {
        return ! $this->isAward() && $this->round > 1;
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
