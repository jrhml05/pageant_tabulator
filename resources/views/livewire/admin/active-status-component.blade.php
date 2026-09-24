<label class="flex min-h-14 cursor-pointer items-center justify-between gap-4 px-5">
    <span class="flex flex-col">
        <span class="font-medium">{{ $label }}</span>
        <span class="text-sm text-ink-2" wire:loading.remove>{{ $is_active ? 'Open to judges' : 'Closed' }}</span>
        <span class="hidden text-sm text-ink-2" wire:loading.block>Saving…</span>
    </span>
    <input type="checkbox" role="switch" wire:model.live="is_active" @checked($is_active) class="peer sr-only">
    <span aria-hidden="true"
        class="relative h-7 w-12 shrink-0 rounded-full bg-line-strong transition-colors peer-checked:bg-accent peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-accent
               after:absolute after:top-1 after:left-1 after:size-5 after:rounded-full after:bg-surface after:shadow-sm after:transition-transform peer-checked:after:translate-x-5"></span>
</label>
