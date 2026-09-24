<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head', ['title' => trim($__env->yieldContent('title'))])
    @livewireStyles
</head>

@php
    $activeStages = \App\Models\Stage::where('is_active', 1)->pluck('stage_name');
@endphp

<body class="min-h-dvh">
    <a href="#main" class="sr-only z-50 rounded-md bg-accent px-4 py-2 text-accent-ink focus:not-sr-only focus:fixed focus:top-3 focus:left-3">Skip to content</a>

    {{-- Drawer on small screens, fixed rail from lg up. --}}
    <div id="admin-drawer" data-open="false" class="group/drawer">
        <div data-drawer-close class="fixed inset-0 z-30 hidden bg-black/40 group-data-[open=true]/drawer:block lg:hidden!"></div>
        <aside
            class="invisible fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col border-r border-line bg-surface transition-transform group-data-[open=true]/drawer:visible group-data-[open=true]/drawer:translate-x-0 lg:visible lg:w-64 lg:translate-x-0">
            <div class="flex h-16 shrink-0 items-center justify-between gap-3 border-b border-line px-5">
                <a href="{{ route('home') }}" class="flex flex-col leading-tight">
                    <span class="font-semibold">Mr. & Ms. LCUAA 2026</span>
                    <span class="text-xs text-ink-2">Tabulation</span>
                </a>
                <button type="button" data-drawer-close class="btn btn-ghost size-11 px-0 lg:hidden" aria-label="Close menu">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto">
                @include('layouts.navigation')
            </div>
        </aside>
    </div>

    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-line bg-surface px-4 sm:px-6">
            <button type="button" data-drawer-toggle aria-controls="admin-drawer" aria-expanded="false"
                class="btn btn-ghost size-11 px-0 lg:hidden" aria-label="Open menu">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>

            <p class="min-w-0 truncate text-sm text-ink-2">
                Judges are scoring:
                @if ($activeStages->isNotEmpty())
                    <a href="{{ route('settings') }}" class="font-semibold text-ink underline-offset-4 hover:underline">{{ $activeStages->join(', ') }}</a>
                @else
                    <a href="{{ route('settings') }}" class="font-semibold text-danger underline-offset-4 hover:underline">no stage is open</a>
                @endif
            </p>

            <div class="ml-auto flex items-center gap-1">
                <x-theme-toggle />
                <span class="hidden px-2 text-sm text-ink-2 sm:inline">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost">
                        <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                        <span class="hidden sm:inline">Log out</span>
                        <span class="sr-only sm:hidden">Log out</span>
                    </button>
                </form>
            </div>
        </header>

        <main id="main" class="mx-auto w-full max-w-screen-2xl px-4 py-6 sm:px-6 lg:py-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    @livewireScripts
</body>

</html>
