@php
    $main = [
        ['Scoring status', 'home', 'fa-list-check', ['home']],
        ['Candidates', 'candidates.index', 'fa-id-badge', ['candidates.*']],
        ['Judges', 'judges.index', 'fa-user-tie', ['judges.*']],
        ['Stages', 'settings', 'fa-sliders', ['settings']],
    ];

    // [label, route]; each report also owns its `{route}_judge{n}` pages.
    $reports = fn (string $d) => [
        'Pre-pageant' => [
            ['Overall', "{$d}_prepageant"],
            ['Rave wear', "{$d}_rave_wear"],
            ['Talent', "{$d}_talent"],
        ],
        'Preliminaries' => [
            ['Overall', "{$d}_prelim"],
            ['National costume', "{$d}_national_costume"],
            ['Departmental uniform', "{$d}_departmental_uniform"],
            ['Swim wear', "{$d}_swim_wear"],
            ['Formal wear', "{$d}_formal_wear"],
            ['Casual Q&A', "{$d}_qna"],
            ['Top 5', "{$d}_top_5"],
        ],
        'Final' => [
            ['Final', "{$d}_final"],
        ],
    ];

    $divisions = ['ms' => 'Ms. LCUAA results', 'mr' => 'Mr. LCUAA results'];
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

    @foreach ($divisions as $d => $divisionLabel)
        @php
            $groups = $reports($d);
            $inDivision = collect($groups)->flatten(1)->contains(fn ($item) => request()->routeIs($item[1], $item[1] . '_judge*'));
        @endphp
        <details class="group" @if ($inDivision) open @endif>
            <summary class="flex min-h-10 cursor-pointer list-none items-center justify-between rounded-md px-3 text-sm font-semibold hover:bg-surface-2 [&::-webkit-details-marker]:hidden">
                {{ $divisionLabel }}
                <i class="fa-solid fa-chevron-down text-xs text-ink-2 transition-transform group-open:rotate-180" aria-hidden="true"></i>
            </summary>
            <div class="mt-1 flex flex-col gap-3 pl-3">
                @foreach ($groups as $groupLabel => $items)
                    <div>
                        @if (count($items) > 1)
                            <p class="px-3 pt-1 pb-1 text-xs font-medium text-ink-2">{{ $groupLabel }}</p>
                        @endif
                        <ul class="flex flex-col gap-0.5">
                            @foreach ($items as [$label, $name])
                                @php $current = request()->routeIs($name, $name . '_judge*'); @endphp
                                <li>
                                    <a href="{{ route($name) }}" class="{{ $linkClass($current) }}" @if ($current) aria-current="page" @endif>
                                        {{ $label }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </details>
    @endforeach
</nav>
