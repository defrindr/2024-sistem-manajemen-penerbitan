@extends('layouts.admin.main')

@section('title', 'Template')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Template</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <form action="{{ route('template-penulisan.store') }}" method="POST" class="form"
                    enctype="multipart/form-data" onsubmit="return confirm('Apakah anda yakin ??')">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="name">Template Word</label>
                                    <input type="file" class="form-control @error('templateWord') is-invalid @enderror"
                                        accept=".doc, .docx" name="templateWord" />
                                    @error('templateWord')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>
                                {{-- Unduh template --}}
                                <div class="form-group">
                                    <a href="{{ route('template-penulisan.download') }}">Unduh Template</a>
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
