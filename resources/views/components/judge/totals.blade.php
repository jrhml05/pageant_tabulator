{{-- Card footer: raw total out of 100, plus the weighted value it contributes to the stage. --}}
@props(['total', 'label' => null, 'weight' => null])

<dl class="flex flex-col gap-1 tabular-nums">
    <div class="flex items-baseline justify-between gap-2">
        <dt class="font-medium">Total</dt>
        <dd><span class="text-xl font-semibold">{{ number_format($total, 2) }}</span> <span class="text-sm text-ink-2">/ 100</span></dd>
    </div>
    @if ($weight)
        <div class="flex items-baseline justify-between gap-2 text-sm text-ink-2">
            <dt>{{ $label }}</dt>
            <dd>{{ number_format((cal_percentage($total, 100) / 100) * $weight, 2) }} / {{ $weight }}</dd>
        </div>
    @endif
</dl>
