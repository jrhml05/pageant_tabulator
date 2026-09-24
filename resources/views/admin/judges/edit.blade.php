@extends('layouts.master')

@section('title', 'Edit judge')

@section('content')
    <x-page-header title="Edit judge" :eyebrow="$data['judge']->name" />
    <x-flash />
    @include('admin.judges.form', ['judge' => $data['judge'], 'action' => route('judges.update', $data['judge']->id)])
@endsection
