@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sub Categories</h1>

    </div>
   @livewire('admin.sub-category-component',['category' => $id])

@endsection
