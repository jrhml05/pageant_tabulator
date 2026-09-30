@extends('judge_app.layouts.app')

@section('title', $segment->short)

@section('content')
    {{-- Only when the tabulator has more than one segment open for this panel. --}}
    @if ($open->count() > 1)
        <nav aria-label="Open segments" class="mb-5 max-w-full overflow-x-auto">
            <ul class="inline-flex min-w-max gap-1 rounded-lg border border-line bg-surface p-1">
                @foreach ($open as $item)
                    @php $current = $item->key === $segment->key; @endphp
                    <li>
                        <a href="{{ route('judge.sheet', $item->key) }}" @if ($current) aria-current="page" @endif
                            class="flex min-h-11 items-center gap-2 rounded-md px-4 font-medium transition-colors {{ $current ? 'bg-accent-soft text-accent-soft-ink' : 'text-ink-2 hover:bg-surface-2 hover:text-ink' }}">
                            @if ($locked->contains($item->key))
                                <i class="fa-solid fa-lock text-sm" aria-hidden="true"></i><span class="sr-only">Locked in:</span>
                            @endif
                            {{ $item->short }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    @endif

    @livewire('judge.score-sheet', ['segment' => $segment->key], key($segment->key))
@endsection
