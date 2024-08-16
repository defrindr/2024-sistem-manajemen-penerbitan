@extends('layouts.admin.main')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Dashboard
    </li>
@endsection

{{-- section js and css for chartjs --}}
@section('css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endsection

@section('script')
    <script>
        const ctx = document.getElementById('myChart');
        let dataChart = @json($ebooksChart);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataChart.labels,
                datasets: [{
                    label: dataChart.year,
                    backgroundColor: '#f87979',
                    data: dataChart.data,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

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
                <div class="col-md-12 mb-4">
                    <div class="card card-default">
                        <div class="card-body">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
