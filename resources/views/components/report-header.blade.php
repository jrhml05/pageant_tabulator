{{--
    Header for every Mr/Ms score report. `route` is the overall report's route name;
    the per-judge pages follow the `{route}_judge{n}` naming used in routes/web.php.
--}}
@props([
    'division',
    'stage',
    'title',
    'route' => null,
    'judge' => null,
    'print' => null,
    'rank' => null,
])

@php
    $divisionLabel = $division === 'mr' ? 'Mr. LCUAA' : 'Ms. LCUAA';
    $heading = $judge ? "{$title}: Judge {$judge}" : "{$title} results";
    $tabs = $route
        ? ['Overall' => $route] + collect(range(1, 3))->mapWithKeys(fn ($n) => ["Judge {$n}" => "{$route}_judge{$n}"])->all()
        : [];
@endphp

<x-page-header :title="$heading" :eyebrow="$stage === $title ? $divisionLabel : $divisionLabel . ' · ' . $stage">
    <x-slot:actions>
        @if ($rank)
            <button type="button" class="btn btn-primary" data-action-url="{{ $rank }}"
                data-busy-label="Ranking…" data-error-label="Ranking">
                <i class="fa-solid fa-ranking-star" aria-hidden="true"></i>
                <span data-label>Rank candidates</span>
            </button>
        @endif
        {{ $slot }}
        @if ($print)
            <a href="{{ $print }}" target="_blank" rel="noopener" class="btn btn-secondary">
                <i class="fa-solid fa-print" aria-hidden="true"></i>
                {{ $judge ? "Print judge {$judge} sheet" : 'Print results' }}
            </a>
        @endif
    </x-slot:actions>
</x-page-header>

<p id="action-status" role="alert" class="-mt-3 mb-4 text-sm font-medium text-danger empty:hidden"></p>

@if ($tabs)
    <nav aria-label="Score sheets" class="mb-5 overflow-x-auto">
        <ul class="inline-flex min-w-max gap-1 rounded-lg border border-line bg-surface p-1">
            @foreach ($tabs as $label => $name)
                @php $current = request()->routeIs($name); @endphp
                <li>
                    <a href="{{ route($name) }}" @if ($current) aria-current="page" @endif
                        class="flex min-h-10 items-center rounded-md px-4 text-sm font-medium transition-colors {{ $current ? 'bg-accent-soft text-accent-soft-ink' : 'text-ink-2 hover:bg-surface-2 hover:text-ink' }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
@endif

<x-flash />
