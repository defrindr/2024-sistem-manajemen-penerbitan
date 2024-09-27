@extends('layouts.admin.main')

@section('title', 'Konfirmasi Pembayaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Konfirmasi Pembayaran
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>#</th>
                            <th>Topik</th>
                            <th>Judul</th>
                            <th>Draft</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th>Royalti</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Oleh</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @if ($pagination->count() <= 0)
                                <tr>
                                    <td colspan="10" class="text-center">Belum terdapat karya yang diajukan</td>
                                </tr>
                            @else
                                @foreach ($pagination->items() as $index => $ebook)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $ebook->theme->name }}</td>
                                        <td>{{ $ebook->title }}</td>
                                        <td>
                                            @if ($ebook->status != 'pending')
                                                <a href="{{ $ebook->draftPath }}" target="_blank" rel="noopener noreferrer">
                                                    Buka
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ $ebook->proofOfPaymentPath }}" target="_blank" rel="noopener noreferrer">
                                                Buka
                                            </a>
                                        </td>
                                        <td>{{ $ebook->status }}</td>
                                        <td>{{ $ebook->royalty }}</td>
                                        <td>{{ $ebook->createdAtFormatted }}</td>
                                        <td>{{ $ebook->author->name }}</td>
                                        <td>
                                            <form action="{{ route('ebook.konfirmasi-pembayaran-action', $ebook) }}" method="post"
                                            
                                            onsubmit="return confirm('Yakin ?')">
                                                @csrf
                                                <button class="btn btn-primary">Konfirmasi</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
