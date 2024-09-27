@extends('layouts.admin.main')

@section('title', 'Edit Topik')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('theme.index') }}">Topik</a></li>
    <li class="breadcrumb-item"><a href="{{ route('theme.show', $theme) }}">
            {{ strlen($theme->name) > 50 ? substr($theme->name, 0, 50) . '...' : $theme->name }}
        </a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Edit
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    {{-- Tombol kembali --}}
                    <a href="{{ route('theme.show', $theme) }}" class="btn btn-danger">Kembali</a>
                </div>
                <form action="{{ route('themes.subThemes.update', compact('theme', 'subTheme')) }}" method="POST"
                    class="form" onsubmit="return confirm('Apakah anda yakin ??')">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="name">Sub Topik</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name" value="{{ old('name') ?? $subTheme->name }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="dueDate">Deadline Pengumpulan</label>
                                    <input type="datetime-local" class="form-control @error('dueDate') is-invalid @enderror"
                                        name="dueDate" id="dueDate"
                                        @if ($theme->multipleAuthor == 0) value="{{ $theme->dueDate }}"
                                         readonly 
                                         @else
                                         value="{{ old('dueDate') ?? $subTheme->dueDate }}" @endif>
                                    @error('dueDate')
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
