<div>
    <div class="mb-6">
        <p class="text-sm font-medium text-ink-2">Ms. LCUAA</p>
        <h1 class="text-2xl font-semibold tracking-tight">Pre-pageant</h1>
        <p class="mt-1 max-w-prose text-ink-2">Your weighted totals for this stage. Pick a category to enter or review its scores.</p>
    </div>
    <nav aria-label="Categories" class="mb-8">
        <ul class="flex flex-wrap gap-2">
            <li><a href="{{ route('judge.app.ms.ravewear.score', $stage) }}" class="btn btn-secondary btn-lg">Rave wear</a></li>
            <li><a href="{{ route('judge.app.ms.talent.score', $stage) }}" class="btn btn-secondary btn-lg">Talent</a></li>
        </ul>
    </nav>

    @if ($records->isEmpty())
        <div class="card px-5 py-8 text-center text-ink-2">No score sheets for you yet. Ask the tabulator to create them.</div>
    @endif

    <x-judge.grid>
        @foreach ($records as $record)
            @php $total = (float) $record->rave_wear + (float) $record->talent; @endphp
            <x-judge.candidate-card division="ms" :record="$record" wire:key="summary-{{ $record->id }}">
                <dl class="flex flex-col gap-2 tabular-nums">
                    <div class="flex items-baseline justify-between gap-2">
                        <dt class="text-ink-2">Rave wear</dt>
                        <dd><span class="font-semibold">{{ is_numeric($record->rave_wear) ? number_format($record->rave_wear, 2) : 'Not scored' }}</span> <span class="text-sm text-ink-2">/ 50</span></dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-2">
                        <dt class="text-ink-2">Talent</dt>
                        <dd><span class="font-semibold">{{ is_numeric($record->talent) ? number_format($record->talent, 2) : 'Not scored' }}</span> <span class="text-sm text-ink-2">/ 50</span></dd>
                    </div>
                </dl>
                <x-slot:footer>
                    <x-judge.totals :total="$total" />
                </x-slot:footer>
            </x-judge.candidate-card>
        @endforeach
    </x-judge.grid>
</div>
