@extends('layouts.admin.main')

@section('title', 'Tambah Sub Topik')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('theme.show', $theme) }}">Topik</a></li>
    <li class="breadcrumb-item"><a href="#">Sub Topik</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Tambah
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
                <form action="{{ route('themes.subThemes.store', $theme) }}" method="POST" class="form"
                    onsubmit="return confirm('Apakah anda yakin ??')">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="name">Sub Topik</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="reviewer1Id">Reviewer 1</label>
                                    <select name="reviewer1Id" id="reviewer1Id" class="form-control">
                                        <option value="">-- Pilih Reviewer --</option>
                                        @foreach ($reviewers as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id === old('reviewer1Id')) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('reviewer1Id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="reviewer2Id">Reviewer 2</label>
                                    <select name="reviewer2Id" id="reviewer2Id" class="form-control">
                                        <option value="">-- Pilih Reviewer --</option>
                                        @foreach ($reviewers as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id === old('reviewer2Id')) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('reviewer2Id')
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
