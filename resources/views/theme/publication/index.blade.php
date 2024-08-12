@extends('layouts.admin.main')

@section('title', 'Publikasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Publikasi
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <a href="{{ route('theme.index') }}" class="btn btn-danger">Kembali</a>
                    {{-- Tombol tambah --}}
                    @admin(true)
                        <a href="{{ route('theme.publication.create', compact('theme')) }}" class="btn btn-success">Tambah
                            Cetakan Baru</a>
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Judul Cerita</td>
                                <td>Ctk Ke</td>
                                <td>Tahun</td>
                                <td>Total Produksi</td>
                                <td>Biaya Produksi</td>
                                {{-- <td>Aksi</td> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagination as $keuangan)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $keuangan->title }}</td>
                                    <td>{{ $keuangan->numberOfPrinting }}</td>
                                    <td>{{ $keuangan->productionYear }}</td>
                                    <td>{{ App\Helpers\StrHelper::currency($keuangan->totalProduction) }}</td>
                                    <td>{{ App\Helpers\StrHelper::currency($keuangan->price, 'Rp') }}</td>
                                    {{-- <td></td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
            keyboard: false
        });

        const openModal = (url) => {
            let container = document.querySelector('#detail-keuangan');

            myModal.show();

            fetch(url).then(res => res.text()).then(res => {
                container.innerHTML = res;
            })
        }
    </script>
@endsection
