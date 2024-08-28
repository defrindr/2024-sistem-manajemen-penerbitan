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
        <div class="col-md-12 mb-3">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <!-- small box -->
                    <div class="small-box bg-info text-white">
                        <div class="inner">
                            <h3>{{ $ebooksNeedReview }}</h3>

                            <p>Butuh review</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('dashboard.component.publish', compact('listPublications'))
    </div>
@endsection
