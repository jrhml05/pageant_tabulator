{{--
    The board's order for tied candidates: each Round 2 placement, or a Round 1 segment's first place.
    Shown on a segment's Overall tab once every judge has locked in. Saving stores every tie with a
    choice; Clear puts one tie back.
--}}
@use('App\Scoring\Points')
@use('App\Scoring\Tabulator')
@use('App\Scoring\TieBreak')
@php
    $round2 = $segment->forFinalistsOnly();
    $names = fn ($candidates) => $candidates->map(fn ($c) => "No. {$c->number}")->join(', ', ' and ');
    $placement = fn (int $rank) => ucfirst(Tabulator::placement($rank, $division) ?? "rank {$rank}");
@endphp

<section aria-labelledby="ties-heading" class="card mt-8 max-w-2xl">
    <form method="POST" action="{{ route('results.segment.ties', [$division, $segment->key]) }}" data-keep-ticks>
        @csrf
        @method('PUT')
        <div class="p-5 sm:p-6">
            <h2 id="ties-heading" class="text-lg font-semibold">{{ $ties->count() === 1 ? 'Settle the tie' : 'Settle ties' }}</h2>
            <p class="mt-1 text-sm text-ink-2">
                Every judge has locked in and these averages are equal to the hundredth, so the board of judges decides.
                Record its decision here: the table, the {{ $round2 ? 'Announcement of winners' : 'winner sheet' }} and the printouts follow it, with a note that the board of judges placed them.
                If a score changes later, the decision is cleared.
            </p>

            {{-- app.js keeps any choice made since the page loaded when this refreshes. --}}
            <div data-refresh-region class="mt-5 flex flex-col divide-y divide-line">
                @foreach ($ties as $tie)
                    @php $picks = TieBreak::picks($segment, $tie['candidates']->count()); @endphp
                    <div class="py-5 first:pt-0 last:pb-0">
                        <h3 class="font-semibold">
                            Tied for {{ TieBreak::place($segment, $tie['rank'], $division) }}:
                            <span class="tabular-nums">{{ $names($tie['candidates']) }}</span>
                            <span class="font-normal text-ink-2">at <span class="tabular-nums">{{ Points::format($tie['average']) }}</span></span>
                        </h3>

                        @for ($i = 0; $i < $picks; $i++)
                            <fieldset class="mt-4">
                                <legend class="text-sm font-medium">{{ $round2 ? $placement($tie['rank'] + $i) : 'Winner' }}</legend>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($tie['candidates'] as $candidate)
                                        <label class="flex min-h-12 min-w-28 cursor-pointer items-center gap-3 rounded-md border border-line-strong px-4 has-checked:border-accent has-checked:bg-accent-soft">
                                            <input type="radio" name="ties[{{ $tie['rank'] }}][{{ $i }}]" value="{{ $candidate->id }}"
                                                @checked(($tie['order'][$i] ?? null) === $candidate->id) class="size-5 accent-accent">
                                            <span class="font-semibold tabular-nums">No. {{ $candidate->number }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endfor

                        <p class="mt-3 text-sm text-ink-2">
                            @if ($round2)
                                {{ $picks === 1 ? 'The other' : 'The one left' }} takes {{ Tabulator::placement($tie['rank'] + $picks, $division) ?? 'rank '.($tie['rank'] + $picks) }}.
                            @elseif ($tie['candidates']->count() > 2)
                                The others stay tied for 2nd.
                            @else
                                The other places 2nd.
                            @endif
                        </p>

                        @if ($tie['order'])
                            <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2">
                                <p class="text-sm font-medium">
                                    <i class="fa-solid fa-check" aria-hidden="true"></i>
                                    Saved: {{ collect($tie['order'])->map(fn ($id) => 'No. '.$tie['candidates']->firstWhere('id', $id)->number)->join(', then ') }}.
                                </p>
                                <button type="submit" name="clear" value="{{ $tie['rank'] }}" class="btn btn-ghost">
                                    <i class="fa-solid fa-rotate-left" aria-hidden="true"></i> Clear decision
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex justify-end border-t border-line px-5 py-4 sm:px-6">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-scale-balanced" aria-hidden="true"></i> {{ $round2 ? 'Save order' : 'Save winner' }}
            </button>
        </div>
    </form>
</section>
