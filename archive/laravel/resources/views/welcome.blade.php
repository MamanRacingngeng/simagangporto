@extends('layouts.app')

@section('title', 'BBKB Yogyakarta - Magang')

@section('content')
    @include('components.hero')
    @include('components.steps')
    @include('components.jobs')
    @include('components.gallery')
@endsection
