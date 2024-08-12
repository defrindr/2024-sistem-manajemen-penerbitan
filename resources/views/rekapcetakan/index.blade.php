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
                    <form action="{{ route('rekapcetakan.index') }}">
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
                                <td>Biaya Produksi</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($publications) == 0)
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
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