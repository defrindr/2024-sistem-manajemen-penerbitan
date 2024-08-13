@extends('layouts.admin.main')

@section('title', 'Publish Judul')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('theme.index') }}">Judul</a></li>
    <li class="breadcrumb-item"><a href="{{ route('theme.show', $theme) }}">{{ $theme->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Publish
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    {{-- Tombol kembali --}}
                    <a href="{{ route('theme.index') }}" class="btn btn-danger">Kembali</a>
                </div>
                <form action="{{ route('theme.publish-action', $theme) }}" method="POST" class="form"
                    onsubmit="return confirm('Apakah anda yakin ??')" enctype="multipart/form-data">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="isbn">ISBN</label>
                                    <input type="text" class="form-control @error('isbn') is-invalid @enderror"
                                        name="isbn" id="isbn" value="{{ old('isbn') ?? $theme->isbn }}">
                                    @error('isbn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="cover">Cover</label>
                                    <input type="file" class="form-control @error('cover') is-invalid @enderror"
                                        name="cover" id="cover" accept="image/*">
                                    @error('isbn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="file">File</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror"
                                        name="file" id="file">
                                    @error('file')
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
