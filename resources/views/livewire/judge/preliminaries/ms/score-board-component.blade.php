<div>
    <div class="mb-6">
        <p class="text-sm font-medium text-ink-2">Ms. LCUAA</p>
        <h1 class="text-2xl font-semibold tracking-tight">Preliminaries</h1>
        <p class="mt-1 max-w-prose text-ink-2">Your weighted totals for this stage. Pick a category to enter or review its scores.</p>
    </div>
    <nav aria-label="Categories" class="mb-8">
        <ul class="flex flex-wrap gap-2">
            <li><a href="{{ route('judge.app.ms.nationalcostume.score', $stage) }}" class="btn btn-secondary btn-lg">National costume</a></li>
            <li><a href="{{ route('judge.app.ms.departmentaluniform.score', $stage) }}" class="btn btn-secondary btn-lg">Departmental uniform</a></li>
            <li><a href="{{ route('judge.app.ms.swimwear.score', $stage) }}" class="btn btn-secondary btn-lg">Swim wear</a></li>
            <li><a href="{{ route('judge.app.ms.formalwear.score', $stage) }}" class="btn btn-secondary btn-lg">Formal wear</a></li>
            <li><a href="{{ route('judge.app.ms.qna.score', $stage) }}" class="btn btn-secondary btn-lg">Casual Q&A</a></li>
        </ul>
    </nav>

    @if ($records->isEmpty())
        <div class="card px-5 py-8 text-center text-ink-2">No score sheets for you yet. Ask the tabulator to create them.</div>
    @endif

    <x-judge.grid>
        @foreach ($records as $record)
            @php $total = (float) $record->national_costume + (float) $record->dept_uniform + (float) $record->swim_wear + (float) $record->formal_wear + (float) $record->qna; @endphp
            <x-judge.candidate-card division="ms" :record="$record" wire:key="summary-{{ $record->id }}">
                <dl class="flex flex-col gap-2 tabular-nums">
                    <div class="flex items-baseline justify-between gap-2">
                        <dt class="text-ink-2">National costume</dt>
                        <dd><span class="font-semibold">{{ is_numeric($record->national_costume) ? number_format($record->national_costume, 2) : 'Not scored' }}</span> <span class="text-sm text-ink-2">/ 20</span></dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-2">
                        <dt class="text-ink-2">Departmental uniform</dt>
                        <dd><span class="font-semibold">{{ is_numeric($record->dept_uniform) ? number_format($record->dept_uniform, 2) : 'Not scored' }}</span> <span class="text-sm text-ink-2">/ 20</span></dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-2">
                        <dt class="text-ink-2">Swim wear</dt>
                        <dd><span class="font-semibold">{{ is_numeric($record->swim_wear) ? number_format($record->swim_wear, 2) : 'Not scored' }}</span> <span class="text-sm text-ink-2">/ 20</span></dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-2">
                        <dt class="text-ink-2">Formal wear</dt>
                        <dd><span class="font-semibold">{{ is_numeric($record->formal_wear) ? number_format($record->formal_wear, 2) : 'Not scored' }}</span> <span class="text-sm text-ink-2">/ 20</span></dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-2">
                        <dt class="text-ink-2">Casual Q&A</dt>
                        <dd><span class="font-semibold">{{ is_numeric($record->qna) ? number_format($record->qna, 2) : 'Not scored' }}</span> <span class="text-sm text-ink-2">/ 20</span></dd>
                    </div>
                </dl>
                <x-slot:footer>
                    <x-judge.totals :total="$total" />
                </x-slot:footer>
            </x-judge.candidate-card>
        @endforeach
    </x-judge.grid>
</div>
