@extends('layouts.master')

@section('title', 'Add candidate')

@section('content')
    <x-page-header title="Add candidate" :eyebrow="$data['label']" />
    <x-flash />
    @include('admin.candidates.form', ['candidate' => null, 'action' => route('candidates.store', $data['division'])])
@endsection
