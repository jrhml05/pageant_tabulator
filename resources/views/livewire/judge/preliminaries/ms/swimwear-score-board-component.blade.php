<div>
    <div class="mb-6">
        <p class="text-sm font-medium text-ink-2">Ms. LCUAA · Preliminaries</p>
        <h1 class="text-2xl font-semibold tracking-tight">Swim wear</h1>
        <p class="mt-1 max-w-prose text-ink-2">Scores save as you type. When every candidate is scored, lock in to send your sheet to the tabulator.</p>
    </div>
    @if ($records->isEmpty())
        <div class="card px-5 py-8 text-center text-ink-2">No score sheets for you yet. Ask the tabulator to create them.</div>
    @endif

    <x-judge.grid>
        @foreach ($records as $index => $record)
            @php $total = (float) $record->body + (float) $record->poise + (float) $record->stage_presence + (float) $record->audience_impact; @endphp
            <x-judge.candidate-card division="ms" :record="$record" wire:key="score-{{ $record->id }}">
                <x-judge.score-field :index="$index" field="body" label="Body proportion" :max="40" :record="$record" />
                <x-judge.score-field :index="$index" field="poise" label="Poise & bearing" :max="30" :record="$record" />
                <x-judge.score-field :index="$index" field="stage_presence" label="Stage presence" :max="20" :record="$record" />
                <x-judge.score-field :index="$index" field="audience_impact" label="Audience impact" :max="10" :record="$record" />
                <x-slot:footer>
                    <x-judge.totals :total="$total" label="Swim wear equivalent" :weight="20" />
                </x-slot:footer>
            </x-judge.candidate-card>
        @endforeach
    </x-judge.grid>

    <x-judge.action-bar>
        <a href="{{ route('judge.app.ms.prelim.score', $stage) }}" class="btn btn-secondary btn-lg">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i> Stage summary
        </a>
        <span wire:loading.inline-flex role="status" class="hidden items-center gap-2 text-sm text-ink-2">
            <i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i> Saving…
        </span>
        @if ($records->isNotEmpty() && $records->every(fn ($r) => $r->is_lock))
            <p class="ml-auto inline-flex items-center gap-2 font-medium"><i class="fa-solid fa-lock" aria-hidden="true"></i> Your scores are locked in</p>
        @elseif ($records->isNotEmpty())
            <button wire:click="lockInscore" wire:loading.attr="disabled" type="button" class="btn btn-primary btn-lg ml-auto">
                <i class="fa-solid fa-lock" aria-hidden="true"></i> Lock in scores
            </button>
        @endif
    </x-judge.action-bar>
</div>
