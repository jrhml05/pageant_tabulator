<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head', ['title' => 'Sign in'])
</head>

@php
    // The event logo (public/assets/img/event-art.jpg) on white, its own background. Without the file the panel
    // falls back to the event name on the logo's crimson.
    $eventArt = is_file(public_path('assets/img/event-art.jpg')) ? 'assets/img/event-art.jpg' : null;
@endphp

<body class="min-h-dvh">
    <div class="grid min-h-dvh lg:grid-cols-[minmax(0,1fr)_minmax(0,28rem)]">
        <div class="{{ $eventArt ? 'bg-white' : 'brand-panel' }} hidden items-center justify-center lg:flex">
            @if ($eventArt)
                <img src="{{ asset($eventArt) }}?v={{ filemtime(public_path($eventArt)) }}" alt="Mr. & Ms. LCUAA 2026"
                    width="1600" height="990" class="max-h-dvh w-full object-contain p-12">
            @else
                <p class="px-10 text-center font-semibold tracking-tight text-white">
                    <span class="block text-5xl">Mr. & Ms.</span>
                    <span class="block text-7xl">LCUAA</span>
                    <span class="mt-4 block text-3xl font-normal text-white/80 tabular-nums">2026</span>
                </p>
            @endif
        </div>

        <main class="flex flex-col justify-center bg-surface px-6 py-10 sm:px-10">
            <div class="mx-auto w-full max-w-sm">
                {{-- Above the form, not in the art panel: that panel is hidden on phones and tablets. --}}
                <x-event-logo class="mb-6 h-24" />
                <p class="text-sm font-medium text-ink-2">Mr. & Ms. LCUAA 2026</p>
                <h1 class="text-2xl font-semibold tracking-tight">Sign in to tabulation</h1>
                <p class="mt-1 text-sm text-ink-2">Judges and the tabulator use the accounts set up for this event.</p>

                <div class="mt-8">
                    @yield('content')
                </div>

                <div class="mt-8 flex justify-end">
                    <x-theme-toggle />
                </div>
            </div>
        </main>
    </div>
</body>

</html>
