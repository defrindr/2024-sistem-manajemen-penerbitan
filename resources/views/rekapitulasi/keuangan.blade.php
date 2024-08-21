@extends('layouts.admin.main')

@section('title', 'Keuangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Keuangan
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <form action="{{ route('rekapitulasi.keuangan') }}" class="flex-grow-1">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari..."
                                value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-default" type="submit">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('rekapitulasi.export-keuangan') }}" class="btn btn-primary ml-auto">
                        Export
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Judul Cerita</td>
                                <td>Total Penjualan</td>
                                <td>Pemasukan</td>
                                <td>Biaya Produksi</td>
                                <td>Sebagai</td>
                                <td>Nama</td>
                                <td>Persentase (%)</td>
                                <td>Profit</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($finances->count() == 0)
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data</td>
                                </tr>
                            @endif
                            @foreach ($finances as $keuangan)
                                @php
                                    $firstDetail = true; // variabel untuk mengecek apakah detail pertama
                                @endphp
                                @foreach ($keuangan->details as $detail)
                                    <tr>
                                        @if ($firstDetail)
                                            <td rowspan="{{ $keuangan->details->count() }}">{{ $loop->parent->index + 1 }}</td>
                                            <td rowspan="{{ $keuangan->details->count() }}">{{ $keuangan->theme->name }} <br />
                                                <b><sup>{{ $keuangan->title }}</sup></b>
                                            </td>
                                            <td rowspan="{{ $keuangan->details->count() }}">{{ $keuangan->sellCount }}</td>
                                            <td rowspan="{{ $keuangan->details->count() }}">{{ App\Helpers\StrHelper::currency(intval($keuangan->income), 'Rp ') }}</td>
                                            <td rowspan="{{ $keuangan->details->count() }}">{{ App\Helpers\StrHelper::currency(intval($keuangan->productionCost), 'Rp') }}</td>
                                        @endif
                                        <td>{{ $detail->role }}</td>
                                        <td>{{ $detail->user ? $detail->user->name : '' }}</td>
                                        <td>{{ $detail->percent }}</td>
                                        <td>{{ App\Helpers\StrHelper::currency(intval($detail->profit), 'Rp') }}</td>
                                    </tr>
                                    @php
                                        $firstDetail = false; // setelah detail pertama, set false
                                    @endphp
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $finances->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
