@extends('layouts.master')

@section('title', 'Add judge')

@section('content')
    <x-page-header title="Add judge" />
    <x-flash />
    @include('admin.judges.form', ['judge' => null, 'action' => route('judges.store')])
@endsection
