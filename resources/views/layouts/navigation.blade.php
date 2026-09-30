@php
    $main = [
        ['Scoring control', 'home', 'fa-sliders', ['home']],
        ['Candidates', 'candidates.index', 'fa-id-badge', ['candidates.*']],
        ['Judges', 'judges.index', 'fa-user-tie', ['judges.*']],
    ];

    // Results open in the division on screen, else the last one viewed; each page has its own Ms./Mr. switch.
    $d = request()->route('division') ?? session('results.division', array_key_first(config('pageant.divisions')));

    // [label, url], grouped by round.
    $reports = [
        'Round 1' => [
            ['Overall and finalists', route('results.round1', $d)],
            ...\App\Scoring\Segment::inRound(1)->map(fn ($s) => [$s->short, route('results.segment', [$d, $s->key])])->values(),
        ],
        'Round 2' => \App\Scoring\Segment::inRound(2)->map(fn ($s) => [$s->label, route('results.segment', [$d, $s->key])])->values()->all(),
    ];

    // Per-judge tabs add ?judge=N, so compare paths only.
    $isCurrent = fn (string $url) => parse_url($url, PHP_URL_PATH) === '/'.ltrim(request()->path(), '/');
    $linkClass = fn (bool $current) => 'flex min-h-10 items-center gap-3 rounded-md px-3 text-sm font-medium transition-colors '
        . ($current ? 'bg-accent-soft text-accent-soft-ink' : 'text-ink-2 hover:bg-surface-2 hover:text-ink');
@endphp

<nav aria-label="Admin" class="flex flex-col gap-6 px-3 py-4">
    <ul class="flex flex-col gap-0.5">
        @foreach ($main as [$label, $name, $icon, $patterns])
            @php $current = request()->routeIs(...$patterns); @endphp
            <li>
                <a href="{{ route($name) }}" class="{{ $linkClass($current) }}" @if ($current) aria-current="page" @endif>
                    <i class="fa-solid {{ $icon }} w-4 text-center" aria-hidden="true"></i>
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="flex flex-col gap-3">
        <p class="px-3 text-xs font-semibold tracking-wide text-ink-2 uppercase">Results</p>
        @foreach ($reports as $groupLabel => $items)
            <div>
                @if (count($items) > 1)
                    <p class="px-3 pb-1 text-xs font-medium text-ink-2">{{ $groupLabel }}</p>
                @endif
                <ul class="flex flex-col gap-0.5">
                    @foreach ($items as [$label, $url])
                        @php $current = $isCurrent($url); @endphp
                        <li>
                            <a href="{{ $url }}" class="{{ $linkClass($current) }}" @if ($current) aria-current="page" @endif>
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</nav>
