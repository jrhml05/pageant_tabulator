<div>
    @if ($stages->isEmpty())
        <div class="card max-w-xl px-5 py-8 text-center text-ink-2">No stages set up. Run <code>php artisan db:seed --class=StageSeeder</code> to create them.</div>
    @else
        <ul class="card max-w-xl divide-y divide-line">
            @foreach ($stages as $stage)
                <li>
                    @livewire('admin.active-status-component', ['model' => $stage, 'field' => 'is_active', 'label' => $stage->stage_name], key($stage->id))
                </li>
            @endforeach
        </ul>
    @endif
</div>
