<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TABULATION APP</title>

    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    @stack('css')
    @livewireStyles()
</head>
<body>
    
    <div class="fixed-top bg-white">
        <h1 class="fixed-top text-center bg-primary text-white p-1 "><i class="fas fa-solid fa-chess-queen rotate-n-15"></i>
         MR. & MS. UEP - {{ strtoupper(App\Models\Stage::where('is_active',1)->first()->stage_name) }} </h1>
    <br><br><br>
        <div class="form-group pl-5 pr-5 pt-1">
            {{-- <button wire:click="try" class="btn btn-primary">dadas</button> --}}
            <div class="row mb-1 justify-content-center">
                @if (App\Models\Stage::where('is_active',1)->first()->id == 1)
                    @php
                        $route_mr = 'judge.app.mr.score';
                        $route_ms = 'judge.app.ms.score';
                    @endphp
                @elseif (App\Models\Stage::where('is_active',1)->first()->id == 2)
                    @php
                        $route_mr = 'judge.app.mr.prelim.score';
                        $route_ms = 'judge.app.ms.prelim.score';
                    @endphp
                @endif
                
                <div class="col-md-2 mb-1">
                    <a href="{{ route($route_mr, $stage) }}" type="button" class="btn btn-primary btn-lg btn-block rounded-pill"><i class="fas fa-solid fa-crown"></i> MR. UEP</a>
                </div>
                <div class="col-md-2 mb-1">
                    <a href="{{ route($route_ms, $stage) }}" type="button" class="btn btn-danger btn-lg btn-block rounded-pill"><i class="fas fa-solid fa-crown"></i> MS. UEP</a>
                </div>
            </div>
        </div>

    </div>

    <div class="container-fluid" >
        {{-- <div class="row mt-4"> --}}
            @yield('content')
            @yield('carousel')
        {{-- </div> --}}
    </div>
    {{-- <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; UEP MIS</span>
            </div>
        </div>
    </footer> --}}
    @stack('scripts')

    @livewireScripts()
</body>
</html>
