@extends('judge_app.layouts.app')

@push('css')
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/carousel/css/bootstrap.min.css') }}" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/carousel/css/owl.carousel.min.css') }}" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/carousel/css/owl.theme.default.min.css') }}" crossorigin="anonymous" />
@endpush


<style>
    .card-counter {
        box-shadow: 2px 2px 10px #DADADA;
        margin: 5px;
        padding: 20px 10px;
        background-color: #fff;
        height: 100px;
        border-radius: 5px;
        transition: .3s linear all;
        cursor: pointer;
    }

    .card-counter:hover {
        box-shadow: 4px 4px 20px #DADADA;
        transition: .3s linear all;
    }

    .card-counter.primary {
        background-color: #007bff;
        color: #FFF;
    }

    .card-counter.danger {
        background-color: #ef5350;
        color: #FFF;
    }

    .card-counter.success {
        background-color: #66bb6a;
        color: #FFF;
    }

    .card-counter.info {
        background-color: #26c6da;
        color: #FFF;
    }

    .card-counter i {
        font-size: 5em;
        opacity: 0.2;
    }

    .card-counter .count-numbers {
        position: absolute;
        right: 35px;
        top: 30px;
        font-size: 32px;
        display: block;
        /* text-align: center; */
    }

    .card-counter .count-name {
        position: absolute;
        right: 35px;
        top: 65px;
        font-style: italic;
        text-transform: capitalize;
        opacity: 0.5;
        display: block;
        font-size: 18px;
    }

    .border-number {
        /* border: 2px solid rgb(64, 10, 211); */
        padding: 25px;
        background: rgb(192, 190, 49);
        border-radius: 50px;
        width: 100px;
        align-content: center;
        font-weight: bold;
        color: white;
    }

    /* //carousel */

    .carousel-img:hover {
        transform: scale(1.1);
        z-index: 1;
        cursor: pointer;
    }

    .carousel-img {
        transition: transform .2s;
        height: 150px;

    }

    .stretch-card>.card {
        width: 100%;
        min-width: 100%
    }

    body {
        background-color: #f9f9fa
    }

    .flex {
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto
    }

    @media (max-width:991.98px) {
        .padding {
            padding: 1.5rem
        }
    }

    @media (max-width:767.98px) {
        .padding {
            padding: 1rem
        }
    }

    .padding {
        padding: 3rem
    }


    .owl-carousel .item {
        margin: 3px;
    }

    .owl-carousel .item img {
        display: block;
        width: 100%;
        height: auto;
    }

    .owl-carousel .item {
        margin: 3px;
    }

    .owl-carousel {
        margin-bottom: 15px;
    }

    /*  */
</style>
@section('content')
    <div class="row mt-4">
        <marquee direction="left">
            <h1>Welcome {{ Auth::user()->name }}!</h1>
        </marquee>
    </div>
    @php
        $colors = ['primary', 'success', 'info', 'danger'];
    @endphp
    <div class="row mt-4">
        @forelse (App\Models\Stage::where('is_active', 1)->get() as $stage)
            {{-- <div class="col-md-12 col-sm-12 mt-3">
            <a href="{{ route('judge.app.score', $stage->id) }}">
                <div class="card-counter {{ $colors[$loop->iteration - 1] }}">
                    <i class="fas fa-solid fa-chess-queen"></i>
                    <span class="count-numbers">{{ strtoupper($stage->stage_name) }}</span>

                </div>
            </a>
        </div> --}}

            <div class="col-md-2"></div>
            <div class="col-md-8">
                <a class="btn btn-{{ $colors[$loop->iteration - 1] }} btn-lg btn-block rounded-pill"
                    href="{{ $stage->id == 4 ? route('judge.app.final.score') : route('judge.app.ms.score', $stage->id) }}">CLICK TO ENTER {{ strtoupper($stage->stage_name) }}
                    SCORE BOARD</a>
            </div>
            <div class="col-md-2"></div>

            {{-- </div> --}}



        @empty
            <center>NO STAGES</center>
        @endforelse

    </div>
@endsection

@section('carousel')
    <div class="row mt-4">
        <div class="col-11 m-auto">
            <div class="owl-carousel owl-theme">
                @foreach (App\Models\Ms_candidate::all() as $ms_candidate)
                    <div class="item mb-4">
                        <div class="card border-0 shadow">
                            <img src="{{ asset('assets/img/ms/' . $ms_candidate->id . '.jpg') }}" alt=""
                                class="card-img-top p-2 carousel-img">
                            <div class="card-body">
                                <div class="card-title text-center">
                                    <center>
                                        <h4 class="border-number mt-2">#{{ strtoupper($ms_candidate->id) }}</h4>
                                    </center>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/carousel/js/jquery.min.js') }}"></script>

    <script src="{{ asset('assets/carousel/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/carousel/js/owl.carousel.min.js') }}"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('assets/carousel/js/bootstrap.bundle.min.js') }}"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
        integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
        crossorigin="anonymous"></script> --}}
    <script>
        // $('.owl-carousel').owlCarousel({
        //     loop: true,
        //     margin: 15,
        //     nav: true,
        //     autoplay: true,
        //     autoplayTimeout: 2000,
        //     autoplayHoverPause: true,
        //     responsive: {
        //         0: {
        //             items: 1
        //         },
        //         600: {
        //             items: 3
        //         },
        //         1000: {
        //             items: 5
        //         },
        //         1600: {
        //             items: 7
        //         }
        //     }
        // })

        var owl = $('.owl-carousel');
        owl.owlCarousel({
            loop: true,
            margin: 15,
            nav: false,
            dots:true,
            autoplay: true,
            slideTransition: 'linear',
            autoplayTimeout: 7000,
            autoplaySpeed: 5000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                },
                1600: {
                    items: 6
                }
            }
        });
        owl.on('mousewheel', '.owl-stage', function(e) {
            if (e.deltaY > 0) {
                owl.trigger('next.owl');
            } else {
                owl.trigger('prev.owl');
            }
            e.preventDefault();
        });


    </script>
@endpush
