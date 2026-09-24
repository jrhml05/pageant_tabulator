@props(['title', 'eyebrow' => null])

{{-- Actions sit beside the title when there is room and drop below it otherwise. --}}
<header class="mb-6 flex flex-wrap items-end justify-between gap-x-8 gap-y-4">
    <div class="max-w-full shrink-0">
        @if ($eyebrow)
            <p class="text-sm font-medium text-ink-2">{{ $eyebrow }}</p>
        @endif
        <h1 class="text-2xl font-semibold tracking-tight text-balance">{{ $title }}</h1>
    </div>
    @isset($actions)
        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</header>
