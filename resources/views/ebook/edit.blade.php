@extends('layouts.admin.main')

@section('title', 'Edit Ebook')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Edit Ebook
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    {{-- Tombol kembali --}}
                    <a href="{{ route('ebook.me') }}" class="btn btn-danger">Kembali</a>
                </div>
                <form action="{{ route('ebook.update', $ebook) }}" method="POST" class="form"
                    onsubmit="return confirm('Apakah anda yakin ??')" enctype="multipart/form-data">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        @method('PUT')
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
                                        name="title" id="title" value="{{ old('title') ?? $ebook->title }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="draft">File Draft</label>
                                    <input type="file" class="form-control @error('draft') is-invalid @enderror"
                                        name="draft" id="draft"
                                        accept="application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/octet-stream">
                                    @error('draft')
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
