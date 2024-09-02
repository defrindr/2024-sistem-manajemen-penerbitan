@extends('layouts.admin.main')

@section('title', 'Keuangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Detail Pembagian Royalti
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <a href="{{ route('theme.keuangan.index', compact('theme')) }}" class="btn btn-danger">Kembali</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Sebagai</td>
                                <td>Nama</td>
                                <td>Persentase</td>
                                <td>Profit</td>
                                <td>Bukti TF</td>
                                @admin(true)
                                    <td>Aksi</td>
                                @endadmin
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($keuangan->details as $detail)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $detail->role }}</td>
                                    <td>{{ $detail->user ? $detail->user->name : '' }}</td>
                                    <td>{{ $detail->percent }}</td>
                                    <td>{{ App\Helpers\StrHelper::currency(intval($detail->profit), 'Rp') }}</td>

                                    <td>
                                        <a href="{{ asset('storage/buktiTf/' . $detail->buktiTf) }}" target="_blank">
                                            <img src="{{ asset('storage/buktiTf/' . $detail->buktiTf) }}" alt=""
                                                class="img img-fluid" style="max-height: 75px">
                                        </a>
                                    </td>
                                    @admin(true)
                                        <td>
                                            <a href="{{ route('theme.keuangan-detail.bukti', compact('theme', 'keuangan', 'detail')) }}"
                                                class="btn btn-primary">
                                                Upload Bukti
                                            </a>
                                        </td>
                                    @endadmin
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
