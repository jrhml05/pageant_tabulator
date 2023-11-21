<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tabulation - Finals</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script src="{{ asset('assets/tailwind/tailwindcss.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>

    @livewireStyles()
</head>
<body >
    <header class="sticky top-0 z-50 mb-4"><h1 class="text-center bg-emerald-600 text-white text-bold p-3 text-2xl">MISS BINALONAN 2023 - TOP 5 - FINAL SCORE BOARD</h1></header>



    @yield('content')

    @stack('scripts')

    @livewireScripts()
</body>
</html>
