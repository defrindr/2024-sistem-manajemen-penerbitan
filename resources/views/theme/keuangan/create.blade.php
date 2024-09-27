@extends('layouts.admin.main')

@section('title', 'Tambah Keuangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('theme.index') }}">Judul</a></li>
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
                <form action="{{ route('theme.keuangan.store', compact('theme')) }}" method="POST" class="form"
                    onsubmit="return confirm('Apakah anda yakin ??')">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="publicationId">Publikasi</label>
                                    <select name="publicationId" id="publicationId" class="form-control"
                                        @error('publicationId') is-invalid @enderror>
                                        <option value="">-- Pilih Publikasi --</option>
                                        @foreach ($publications as $item)
                                            <option value="{{ $item->id }}"
                                                @if (old('publicationId') == $item->id) selected @endif>Cetakan
                                                Ke-{{ $item->numberOfPrinting }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('publicationId')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="title">Judul</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" id="title" value="{{ old('title') }}" readonly>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="productionCost">Biaya Produksi</label>
                                    <input type="number" class="form-control @error('productionCost') is-invalid @enderror"
                                        name="productionCost" id="productionCost" value="{{ old('productionCost') }}"
                                        readonly>
                                    @error('productionCost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="sellPrice">Harga Jual Perbuku</label>
                                    <input type="number" class="form-control @error('sellPrice') is-invalid @enderror"
                                        name="sellPrice" id="sellPrice" value="{{ old('sellPrice') }}">
                                    @error('sellPrice')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="sellCount">Total Penjualan</label>
                                    <input type="number" class="form-control @error('sellCount') is-invalid @enderror"
                                        name="sellCount" id="sellCount" value="{{ old('sellCount') }}">
                                    @error('sellCount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="year">Tahun Penjualan</label>
                                    <input type="number" class="form-control @error('year') is-invalid @enderror"
                                        name="year" id="year" value="{{ old('year') }}">
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="percentAdmin">Admin (%)</label>
                                    <input type="number" class="form-control @error('percentAdmin') is-invalid @enderror"
                                        min="0" name="percentAdmin" id="percentAdmin"
                                        value="{{ old('percentAdmin') }}">
                                    @error('percentAdmin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="percentReviewer">Reviewer (%)</label>
                                    <input type="number"
                                        class="form-control @error('percentReviewer') is-invalid @enderror" min="0"
                                        name="percentReviewer" id="percentReviewer" value="{{ old('percentReviewer') }}">
                                    @error('percentReviewer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="percentAuthor">Author (%)</label>
                                    <input type="number" class="form-control @error('percentAuthor') is-invalid @enderror"
                                        min="0" name="percentAuthor" id="percentAuthor"
                                        value="{{ old('percentAuthor') }}">
                                    @error('percentAuthor')
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
        let publications = @json($publications);

        function hitungPersenAuthor() {
            let nilaiAdmin = document.querySelector('#percentAdmin').value;
            let persenAdmin = parseFloat(nilaiAdmin !== '' ? nilaiAdmin : '0');
            let nilaiReviewer = document.querySelector('#percentReviewer').value;
            let persenReviewer = parseFloat(nilaiReviewer !== '' ? nilaiReviewer : '0');

            console.log(100 - (persenAdmin + persenReviewer))

            document.querySelector('#percentAuthor').value = 100 - (persenAdmin + persenReviewer)
        }

        document.querySelector('#percentAdmin').addEventListener('keydown', () => hitungPersenAuthor());
        document.querySelector('#percentReviewer').addEventListener('keydown', () => hitungPersenAuthor());
        document.querySelector('#percentAdmin').addEventListener('keyup', () => hitungPersenAuthor());
        document.querySelector('#percentReviewer').addEventListener('keyup', () => hitungPersenAuthor());

        // On publicationId changed, fill name & productionCost
        document.querySelector('#publicationId').addEventListener('change', (event) => {
            const publication = publications.find(publication => publication.id == event.target.value);
            if (publication) {
                document.querySelector('#title').value =
                    `Cetakan Ke-${publication.numberOfPrinting} Tahun ${publication.productionYear}`;
                document.querySelector('#productionCost').value = publication.totalProduction * publication.price;
                document.querySelector('#sellCount').value = publication.totalProduction;
                document.querySelector('#sellPrice').value = publication.price;
                document.querySelector('#year').value = publication.productionYear;
            }
        })

        hitungPersenAuthor();
    </script>
@endsection
