<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head', ['title' => trim($__env->yieldContent('title'))])
    @livewireStyles
</head>

@php
    $judge = Auth::user();
    $openNow = $judge->panel ? \App\Scoring\Segment::open($judge->panel)->pluck('short')->values() : collect();
@endphp

{{-- app.js polls data-judge-watch and shows the banner below when the open segments change. --}}
<body class="min-h-dvh" data-judge-watch="{{ route('judge.status') }}" data-open='@json($openNow)'
    @hasSection('waiting') data-judge-waiting="{{ route('judge.app') }}" @endif>
    <header class="sticky top-0 z-30 border-b border-line bg-surface">
        <div class="mx-auto flex min-h-16 max-w-screen-2xl items-center gap-x-4 px-4 py-2 sm:px-6">
            <a href="{{ route('judge.app') }}" class="mr-auto flex min-w-0 flex-col leading-tight">
                <span class="font-semibold">{{ config('pageant.event') }}</span>
                <span class="truncate text-sm text-ink-2">
                    {{ $judge->name }}{{ $judge->panel ? ' · '.config("pageant.panels.{$judge->panel}").' panel' : '' }}
                </span>
            </a>

            <div class="flex items-center gap-1">
                <x-theme-toggle />
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost min-h-11">
                        <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                        <span class="hidden sm:inline">Log out</span>
                        <span class="sr-only sm:hidden">Log out</span>
                    </button>
                </form>
            </div>
        </div>

        <div data-segment-banner hidden class="border-t border-line bg-accent-soft text-accent-soft-ink">
            <div class="mx-auto flex max-w-screen-2xl flex-wrap items-center justify-between gap-x-4 gap-y-2 px-4 py-2 sm:px-6">
                <p role="status" data-segment-banner-text class="font-medium"></p>
                <a href="{{ route('judge.app') }}" class="btn btn-primary btn-lg">Go to it</a>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-screen-2xl px-4 pt-6 pb-36 sm:px-6">
        <x-flash />
        @yield('content')
    </main>

    @livewireScripts
</body>

</html>
