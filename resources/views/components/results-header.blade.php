{{--
    Header for every results page: a Ms./Mr. switch (same page, other division) and optional judge tabs
    (`tabs` is [label => url]; the current one matches the request URL).
    app.js re-fetches the page every few seconds and swaps each [data-refresh-region] in place.
--}}
@props(['title', 'eyebrow' => null, 'tabs' => [], 'print' => null, 'printLabel' => 'Print results'])

@php
    // The same page for the other division, keeping any ?judge= tab.
    $route = request()->route();
    $divisionUrl = fn (string $division) => route($route->getName(), ['division' => $division] + $route->parameters())
        .(request()->getQueryString() ? '?'.request()->getQueryString() : '');
@endphp

<x-page-header :title="$title" :eyebrow="$eyebrow">
    <x-slot:actions>
        <nav aria-label="Division">
            <ul class="inline-flex gap-1 rounded-lg border border-line bg-surface p-1">
                @foreach (config('pageant.divisions') as $division => $label)
                    @php $current = $route->parameter('division') === $division; @endphp
                    <li>
                        <a href="{{ $divisionUrl($division) }}" @if ($current) aria-current="page" @endif
                            class="flex min-h-10 items-center rounded-md px-4 text-sm font-semibold transition-colors {{ $current ? 'bg-accent-soft text-accent-soft-ink' : 'text-ink-2 hover:bg-surface-2 hover:text-ink' }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
        {{ $slot }}
        @if ($print)
            <a href="{{ $print }}" target="_blank" rel="noopener" class="btn btn-secondary">
                <i class="fa-solid fa-print" aria-hidden="true"></i> {{ $printLabel }}
            </a>
        @endif
    </x-slot:actions>
</x-page-header>

<div class="mb-5 flex flex-wrap items-center justify-between gap-x-4 gap-y-2">
    @if ($tabs)
        <nav aria-label="Score sheets" class="max-w-full overflow-x-auto">
            <ul class="inline-flex min-w-max gap-1 rounded-lg border border-line bg-surface p-1">
                @foreach ($tabs as $label => $url)
                    @php $current = $url === request()->fullUrl(); @endphp
                    <li>
                        <a href="{{ $url }}" @if ($current) aria-current="page" @endif
                            class="flex min-h-10 items-center rounded-md px-4 text-sm font-medium transition-colors {{ $current ? 'bg-accent-soft text-accent-soft-ink' : 'text-ink-2 hover:bg-surface-2 hover:text-ink' }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    @endif
    <p data-live-report class="text-sm text-ink-2">
        <span data-live-note role="status" class="font-medium text-ink empty:hidden"></span>
        <span data-live-time>Scores refresh automatically.</span>
    </p>
</div>

<x-flash />
