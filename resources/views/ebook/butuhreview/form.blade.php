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
                    <a href="{{ url()->previous() }}" class="btn btn-default">Kembali</a>
                </div>
                <form action="{{ route('ebook.butuhreview.action', $ebook) }}" method="POST" class="form"
                    onsubmit="return confirm('Apakah anda yakin ??')" enctype="multipart/form-data">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="themeId">Topik</label>
                                    <input type="hidden" class="form-control" name="themeId" id="themeId"
                                        value="{{ $ebook->theme->id }}">
                                    <input type="text" class="form-control @error('themeId') is-invalid @enderror"
                                        name="themeName" id="themeName" value="{{ $ebook->theme->name }}" disabled>
                                    @error('themeId')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="title">Judul</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" id="title" value="{{ old('title') ?? $ebook->title }}" disabled>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="title">Hasil Review</label>
                                    <select name="acc" id="acc"
                                        class="form-control  @error('acc') is-invalid @enderror">
                                        <option value="">-- Pilih Hasil Review --</option>
                                        <option value="1">Setujui</option>
                                        <option value="-1">Revisi</option>
                                    </select>
                                    @error('acc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="comment">Komentar</label>
                                    <textarea name="comment" id="comment" cols="30" rows="10"
                                        class="form-control @error('comment') is-invalid @enderror">{{ old('comment') ?? $ebook->comment }}</textarea>
                                    @error('comment')
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
