@extends('layouts.master')

@section('content')
    <style>
        #card {
            cursor: pointer;
            transition: transform .2s;
            /* Animation */
            margin: 0 auto;
        }

        #card:hover {
            transform: scale(1.3);
            z-index: 1;
        }

        .modal .modal-dialog-aside {
            width: 350px;
            max-width: 80%;
            height: 100%;
            margin: 0;
            transform: translate(0);
            transition: transform .2s;
        }

        .modal .modal-dialog-aside .modal-content {
            height: inherit;
            border: 0;
            border-radius: 0;
        }

        .modal .modal-dialog-aside .modal-content .modal-body {
            overflow-y: auto
        }

        .modal.fixed-left .modal-dialog-aside {
            margin-left: auto;
            transform: translateX(100%);
        }

        .modal.fixed-right .modal-dialog-aside {
            margin-right: auto;
            transform: translateX(-100%);
        }

        .modal.show .modal-dialog-aside {
            transform: translateX(0);
        }
    </style>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Candidates</h1>
        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-plus fa-sm text-white-50"></i> ADD NEW</a> --}}
    </div>


    @livewire('admin.candidates-component')

@endsection
