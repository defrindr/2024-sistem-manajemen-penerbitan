@extends('layouts.admin.main')

@section('title', 'Tambah Cetakan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('theme.index') }}">Judul</a></li>
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
                    <a href="{{ route('theme.publication.index', compact('theme')) }}" class="btn btn-danger">Kembali</a>
                </div>
                <form action="{{ route('theme.publication.store', compact('theme')) }}" method="POST" class="form"
                    onsubmit="return confirm('Apakah anda yakin ??')" enctype="multipart/form-data">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="title">Judul</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" id="title" value="{{ old('title') ?? $theme->name }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="cover">Cover</label>
                                    <input type="file" class="form-control @error('cover') is-invalid @enderror"
                                        name="cover" id="cover" value="{{ old('cover') ?? $theme->name }}">
                                    @error('cover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="numberOfPrinting">Cetakan Ke</label>
                                    <input type="number"
                                        class="form-control @error('numberOfPrinting') is-invalid @enderror"
                                        name="numberOfPrinting" id="numberOfPrinting"
                                        value="{{ old('numberOfPrinting') ?? $theme->publications()->count() + 1 }}">
                                    @error('numberOfPrinting')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="productionYear">Tahun Produksi</label>
                                    <input type="year" class="form-control @error('productionYear') is-invalid @enderror"
                                        name="productionYear" id="productionYear"
                                        value="{{ old('productionYear') ?? date('Y') }}">
                                    @error('productionYear')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="price">Harga Per Buku</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        min="0" name="price" id="price" value="{{ old('price') }}">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="totalProduction">Jumlah Produksi</label>
                                    <input type="number" min="0"
                                        class="form-control @error('totalProduction') is-invalid @enderror"
                                        name="totalProduction" id="totalProduction" value="{{ old('totalProduction') }}">
                                    @error('totalProduction')
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
