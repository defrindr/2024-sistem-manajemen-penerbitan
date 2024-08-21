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
        let dataChart = @json($ebooks);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dataChart.map(item => item.month),
                datasets: [{
                    label: 'Sub Judul Selesai Di buat Berdasarkan Bulan',
                    backgroundColor: '#f87979',
                    data: dataChart.map(item => item.total),
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
                <div class="col-md-6">
                    <!-- small box -->
                    <div class="small-box bg-info text-white">
                        <div class="inner">
                            <h3>{{ $totalAuthors }}</h3>

                            <p>Author</p>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-md-6">
                    <!-- small box -->
                    <div class="small-box bg-success text-white">
                        <div class="inner">
                            <h3>
                                {{ $publishedThemes }}
                            </h3>

                            <p>
                                Judul dipublish
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-4 mt-3">
                    <div class="card card-default">
                        <div class="card-body">

                            <canvas id="myChart" width="800" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
