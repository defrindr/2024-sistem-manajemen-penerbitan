@extends('layouts.admin.main')

@section('title', 'Edit Topik')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('theme.index') }}">Topik</a></li>
    <li class="breadcrumb-item"><a href="{{ route('theme.show', $theme) }}">{{ $theme->name }}</a></li>
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
                    <a href="{{ route('theme.index') }}" class="btn btn-default">Kembali</a>
                </div>
                <form action="{{ route('theme.update', $theme) }}" method="POST" class="form"
                    onsubmit="return confirm('Apakah anda yakin ??')">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="categoryId">Kategori</label>
                                    <select name="categoryId" id="categoryId" class="form-control">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id === (old('categoryId') ?? $theme->categoryId)) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoryId')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="name">Topik</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name" value="{{ old('name') ?? $theme->name }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="dueDate">Deadline Pengumpulan</label>
                                    <input type="date" class="form-control @error('dueDate') is-invalid @enderror"
                                        name="dueDate" id="dueDate" value="{{ old('dueDate') ?? $theme->dueDate }}">
                                    @error('dueDate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="price">Biaya Pendaftaran</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        name="price" id="price" value="{{ old('price') ?? $theme->price }}">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="description">Deskripsi Topik</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description"
                                        cols="30" rows="10">{{ old('description') ?? $theme->description }}</textarea>
                                    @error('description')
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
                                            <option value="{{ $item->id }}" data-category="{{ $item->categoryId }}"
                                                @if ($item->id === (old('reviewer1Id') ?? $theme->reviewer1Id)) selected @endif>{{ $item->name }}
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
                                            <option value="{{ $item->id }}" data-category="{{ $item->categoryId }}"
                                                @if ($item->id === (old('reviewer2Id') ?? $theme->reviewer2Id)) selected @endif>{{ $item->name }}
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

@section('script')
    <script>
        function changeopts(show) {
            document.querySelectorAll(`#reviewer1Id option`)?.forEach(el => {
                el.setAttribute('style', 'display: none');
            })
            document.querySelectorAll(`#reviewer1Id option[data-category="${show}"]`)?.forEach(el => {
                el.setAttribute('style', 'display: block');
            })
            document.querySelectorAll(`#reviewer2Id option`)?.forEach(el => {
                el.setAttribute('style', 'display: none');
            })
            document.querySelectorAll(`#reviewer2Id option[data-category="${show}"]`)?.forEach(el => {
                el.setAttribute('style', 'display: block');
            })
        }


        changeopts(document.querySelector('#categoryId').value);
        document.querySelector('#categoryId').addEventListener('change', (event) => {
            changeopts(document.querySelector('#categoryId').value);
            if (event.target.value) {
                document.querySelector('#reviewer1Id').removeAttribute('disabled');
                document.querySelector('#reviewer2Id').removeAttribute('disabled');
            } else {
                document.querySelector('#reviewer1Id').value = '';
                document.querySelector('#reviewer1Id').setAttribute('disabled', true);
                document.querySelector('#reviewer2Id').value = '';
                document.querySelector('#reviewer2Id').setAttribute('disabled', true);
            }
        })
    </script>
@endsection