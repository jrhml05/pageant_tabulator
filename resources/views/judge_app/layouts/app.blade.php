<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head', ['title' => trim($__env->yieldContent('title'))])
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    @livewireStyles
</head>

@php
    $activeStage = \App\Models\Stage::where('is_active', 1)->orderBy('id')->first();
    $boardRoutes = [1 => '', 2 => '.prelim', 3 => '.final'];
    $divisionRoute = fn (string $d) => $activeStage && isset($boardRoutes[$activeStage->id])
        ? route("judge.app.{$d}{$boardRoutes[$activeStage->id]}.score", $activeStage->id)
        : null;
    $currentDivision = request()->routeIs('judge.app.mr.*') ? 'mr' : (request()->routeIs('judge.app.ms.*') ? 'ms' : null);
@endphp

<body class="min-h-dvh">
    <header class="sticky top-0 z-30 border-b border-line bg-surface">
        <div class="mx-auto flex min-h-16 max-w-screen-2xl flex-wrap items-center gap-x-4 gap-y-2 px-4 py-2 sm:px-6">
            <a href="{{ route('judge.app') }}" class="mr-auto flex flex-col leading-tight">
                <span class="font-semibold">Mr. & Ms. LCUAA 2026</span>
                <span class="text-sm text-ink-2">{{ $activeStage?->stage_name ?? 'No stage open' }}</span>
            </a>

            @if ($divisionRoute('ms'))
                <nav aria-label="Division" class="order-last w-full sm:order-none sm:w-auto">
                    <ul class="grid grid-cols-2 gap-1 rounded-lg border border-line bg-surface-2 p-1">
                        @foreach (['ms' => 'Ms. LCUAA', 'mr' => 'Mr. LCUAA'] as $d => $label)
                            <li>
                                <a href="{{ $divisionRoute($d) }}" @if ($currentDivision === $d) aria-current="page" @endif
                                    class="flex min-h-11 items-center justify-center rounded-md px-5 font-medium transition-colors {{ $currentDivision === $d ? 'bg-surface text-ink shadow-sm' : 'text-ink-2 hover:text-ink' }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            <div class="flex items-center gap-1">
                <x-theme-toggle />
                <span class="hidden px-2 text-sm text-ink-2 md:inline">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost min-h-11">
                        <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                        <span class="sr-only">Log out</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-screen-2xl px-4 pt-6 pb-32 sm:px-6">
        @yield('content')
    </main>

    @stack('scripts')
    @livewireScripts
    <script>
        // Scoreboard components dispatch these; see confirmedLockInScores() in each component.
        window.addEventListener('swal:modal', (event) => {
            swal({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
                buttons: event.detail.button ?? 'OK',
            });
        });

        window.addEventListener('swal:confirm', (event) => {
            swal({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
                buttons: ['Cancel', 'Lock in'],
            }).then((confirmed) => {
                if (confirmed) Livewire.dispatch('confirmedLockInScores');
            });
        });
    </script>
</body>

</html>
