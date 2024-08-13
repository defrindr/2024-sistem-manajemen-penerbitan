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
                    <form action="{{ route('rekapitulasi.cetakan') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari..."
                                value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-default" type="submit">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>
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
                                <td>Harga Produksi Buku</td>
                                <td>Total Biaya</td>
                                @admin(true)
                                    <td>Aksi</td>
                                @endadmin
                            </tr>
                        </thead>
                        <tbody>
                            @if ($publications->count() == 0)
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data</td>
                                </tr>
                            @endif
                            @foreach ($publications as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->numberOfPrinting }}</td>
                                    <td>{{ $item->productionYear }}</td>
                                    <td>{{ App\Helpers\StrHelper::currency($item->totalProduction) }}</td>
                                    <td>{{ App\Helpers\StrHelper::currency($item->price, 'Rp') }}</td>
                                    <td>{{ App\Helpers\StrHelper::currency($item->price * $item->totalProduction, 'Rp') }}
                                    </td>
                                    @admin(true)
                                        <td>
                                            @php
                                                $theme = $item->theme;
                                                $publication = $item;
                                            @endphp
                                            <form
                                                action="{{ route('theme.publication.destroy', compact('theme', 'publication')) }}"
                                                method="post" onsubmit="return confirm('Yakin ingin menjalankan aksi ini ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    @endadmin
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $publications->links() }}
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
