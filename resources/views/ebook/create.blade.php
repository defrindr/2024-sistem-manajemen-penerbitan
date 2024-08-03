@extends('layouts.admin.main')

@section('title', 'Tambah Ebook')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Tambah Ebook
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    {{-- Tombol kembali --}}
                    <a href="{{ route('theme.show', $theme) }}" class="btn btn-default">Kembali</a>
                </div>
                <form action="{{ route('ebook.store', compact('theme', 'subTheme')) }}" method="POST" class="form"
                    onsubmit="return confirm('Apakah anda yakin ??')" enctype="multipart/form-data">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="themeId">Topik</label>
                                    <input type="hidden" class="form-control" name="themeId" id="themeId"
                                        value="{{ $theme->id }}">
                                    <input type="text" class="form-control @error('themeId') is-invalid @enderror"
                                        name="themeName" id="themeName" value="{{ $theme->name }}" disabled>
                                    @error('themeId')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="themeId">Sub Topik</label>
                                    <input type="hidden" class="form-control" name="themeId" id="themeId"
                                        value="{{ $subTheme->id }}">
                                        <input type="hidden" class="form-control" name="title" id="title"
                                            value="{{ $theme->name . " - " . $subTheme->name }}">
                                    <input type="text" class="form-control @error('themeId') is-invalid @enderror"
                                        name="themeName" id="themeName" value="{{ $subTheme->name }}" disabled>
                                    @error('themeId')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="price">Harga</label>
                                    <input type="text" class="form-control @error('price') is-invalid @enderror"
                                        name="themeName" id="themeName" value="{{ \App\Helpers\StrHelper::currency($theme->price) }}" disabled>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="proofOfPayment">Bukti Pendaftaran</label>
                                    <input type="file" class="form-control @error('proofOfPayment') is-invalid @enderror"
                                        name="proofOfPayment" id="proofOfPayment">
                                    @error('proofOfPayment')
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
