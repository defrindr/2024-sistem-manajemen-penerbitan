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
                <div class="card-header">
                    <a href="{{ route('theme.index') }}" class="btn btn-danger">Kembali</a>
                    {{-- Tombol tambah --}}
                    @admin(true)
                        <a href="{{ route('theme.keuangan.create', compact('theme')) }}" class="btn btn-success"
                            onclick="return confirm('Pastikan anda telah membuat cetakan sebelumnya, untuk menambahkan keuangan')">Tambah
                            Pemasukan</a>
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Judul Buku</td>
                                <td>Pemasukan</td>
                                <td>Biaya Produksi</td>
                                <td>Sebagai</td>
                                <td>Nama</td>
                                <td>Persentase</td>
                                <td>Profit</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($pagination->count() == 0)
                                <tr>
                                    <td colspan="8" class="text-center">Data Kosong</td>
                                </tr>
                            @else
                                @php $index = 1; @endphp <!-- Inisialisasi variabel untuk penomoran -->
                                @foreach ($pagination as $keuangan)
                                    @foreach ($keuangan->details as $detail)
                                        <tr>
                                            @if ($loop->first)
                                                <td rowspan="{{ $keuangan->details->count() }}">{{ $index++ }}</td>
                                                <td rowspan="{{ $keuangan->details->count() }}">{{ $keuangan->title }}</td>
                                                <td rowspan="{{ $keuangan->details->count() }}">{{ $keuangan->income }}</td>
                                                <td rowspan="{{ $keuangan->details->count() }}">
                                                    {{ App\Helpers\StrHelper::currency(intval($keuangan->productionCost), 'Rp') }}
                                                </td>
                                            @endif
                                            <td>{{ $detail->role }}</td>
                                            <td>{{ $detail->user ? $detail->user->name : '' }}</td>
                                            <td>{{ $detail->percent }}</td>
                                            <td>{{ App\Helpers\StrHelper::currency(intval($detail->profit), 'Rp') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
