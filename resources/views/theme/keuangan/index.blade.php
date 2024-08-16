@extends('layouts.admin.main')

@section('title', 'Keuangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Keuangan
    </li>
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Keuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detail-keuangan">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <a href="{{ route('theme.index') }}" class="btn btn-danger">Kembali</a>
                    {{-- Tombol tambah --}}
                    @admin(true)
                        <a href="{{ route('theme.keuangan.create', compact('theme')) }}" class="btn btn-success"
                            onclick="return confirm('Pastikan anda telah membuat cetakan sebelumnya, untuk menambahkan keuangan')">Tambah
                            Pemasukan</a>
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Judul Cerita</td>
                                <td>Pemasukan</td>
                                <td>Biaya Produksi</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($pagination->count() == 0)
                                <tr>
                                    <td colspan="7" class="text-center">Data Kosong</td>
                                </tr>
                            @else
                                @foreach ($pagination as $keuangan)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $keuangan->title }}</td>
                                        <td>{{ $keuangan->income }}</td>
                                        <td>{{ App\Helpers\StrHelper::currency(intval($keuangan->productionCost), 'Rp') }}
                                        </td>
                                        <td>
                                            <button
                                                onclick="openModal('{{ route('theme.keuangan.show', compact('theme', 'keuangan')) }}')"
                                                class="btn-detail-keuangan btn btn-primary">
                                                Detail
                                            </button>
                                            {{-- cek kembali di routes untuk memastikan --}}
                                            <form
                                                action="{{ route('theme.keuangan.destroy', compact('keuangan', 'theme')) }}"
                                                method="post" onsubmit="return confirm('Apakah anda yakin ??')"
                                                class="d-inline-block">
                                                {{-- Agar tidak expired ketika di submit --}}
                                                @csrf
                                                {{-- Tombol Delete --}}
                                                @method('DELETE')
                                                <button class="btn btn-danger">Hapus</button>
                                            </form>
                                        </td>
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
