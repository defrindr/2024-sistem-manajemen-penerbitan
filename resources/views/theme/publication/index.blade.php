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
                                <td>Cover</td>
                                <td>Judul Cerita</td>
                                <td>Ctk Ke</td>
                                <td>Tahun</td>
                                <td>Total Produksi</td>
                                <td>Biaya Produksi</td>
                                @admin(true)
                                    <td>Aksi</td>
                                @endadmin
                            </tr>
                        </thead>
                        <tbody>
                            @if ($pagination->count() == 0)
                                <tr>
                                    <td colspan="7" class="text-center">Data Kosong</td>
                                </tr>
                            @else
                                @foreach ($pagination as $publication)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <img src="{{ asset($publication->coverLink) }}" alt="{{ $publication->title }}"
                                                srcset="" class="img img-fluid"
                                                style="max-width: 100px;max-height:100px">
                                        </td>
                                        <td>{{ $publication->title }}</td>
                                        <td>{{ $publication->numberOfPrinting }}</td>
                                        <td>{{ $publication->productionYear }}</td>
                                        <td>{{ App\Helpers\StrHelper::currency($publication->totalProduction) }}</td>
                                        <td>{{ App\Helpers\StrHelper::currency($publication->price, 'Rp') }}</td>
                                        @admin(true)
                                            <td>
                                                @php
                                                    $theme = $publication->theme;
                                                @endphp
                                                <a href="{{ route('theme.publication.show', compact('theme', 'publication')) }}"
                                                    class="btn btn-primary mb-2">
                                                    Lihat
                                                </a>
                                                {{-- cek kembali di routes untuk memastikan --}}
                                                <form
                                                    action="{{ route('theme.publication.destroy', compact('publication', 'theme')) }}"
                                                    method="post" onsubmit="return confirm('Apakah anda yakin ??')"
                                                    class="d-inline-block">
                                                    {{-- Agar tidak expired ketika di submit --}}
                                                    @csrf
                                                    {{-- Tombol Delete --}}
                                                    @method('DELETE')
                                                    <button class="btn btn-danger  mb-2">Hapus</button>
                                                </form>
                                            </td>
                                        @endadmin
                                    </tr>
                                @endforeach
                            @endif
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
