@extends('layouts.admin.main')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Dashboard
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
        </div>
    </div>
@endsection
