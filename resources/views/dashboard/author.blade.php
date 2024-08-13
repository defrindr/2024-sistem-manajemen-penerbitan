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
            <div class="row">
                <div class="col-md-4 mb-2">
                    <!-- small box -->
                    <div class="small-box bg-info text-white">
                        <div class="inner">
                            <h3>{{ $totalThemeOpen }}</h3>

                            <p>Judul Di Buka</p>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-md-4 mb-2">
                    <!-- small box -->
                    <div class="small-box bg-success text-white">
                        <div class="inner">
                            <h3>
                                {{ $myEbooksDraft }}
                            </h3>

                            <p>
                                Pengajuan Draft
                            </p>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-md-4 mb-2">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>
                                {{ $myEbooksPublish }}
                            </h3>

                            <p>
                                Pengajuan Selesai
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
