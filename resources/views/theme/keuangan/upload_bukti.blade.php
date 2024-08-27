@extends('layouts.admin.main')

@section('title', 'Tambah Keuangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('theme.index') }}">Judul</a></li>
    <li class="breadcrumb-item"><a href="{{ route('theme.keuangan.index', compact('theme')) }}">Keuangan</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Tambah Keuangan
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    {{-- Tombol kembali --}}
                    <a href="{{ route('theme.keuangan.index', compact('theme')) }}" class="btn btn-danger">Kembali</a>
                </div>
                <form action="{{ route('theme.keuangan-detail.bukti-store', compact('theme', 'keuangan', 'detail')) }}"
                    enctype="multipart/form-data" method="POST" class="form"
                    onsubmit="return confirm('Apakah anda yakin ??')">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="buktiTf">Bukti Transfer</label>
                                    <input type="file" class="form-control @error('buktiTf') is-invalid @enderror"
                                        name="buktiTf" id="buktiTf" value="{{ old('buktiTf') }}" readonly>
                                    @error('buktiTf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection
