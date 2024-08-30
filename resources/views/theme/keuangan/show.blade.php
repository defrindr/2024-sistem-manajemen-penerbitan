@extends('layouts.admin.main')

@section('title', 'Keuangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Detail Pembagian Royalti
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
                {{-- <div class="card-header d-flex justify-content-between align-items-center">
                    <form action="{{ route('rekapitulasi.keuangan') }}" class="flex-grow-1">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari..."
                                value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-default" type="submit">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('rekapitulasi.export-keuangan') }}" class="btn btn-primary ml-auto">
                        Export
                    </a>
                </div> --}}
                <div class="card-header">
                    {{-- Tombol kembali --}}
                    <a href="{{ url()->previous() }}" class="btn btn-danger">Kembali</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Sebagai</td>
                                <td>Nama</td>
                                <td>Persentase</td>
                                <td>Profit</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($keuangan->details as $detail)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $detail->role }}</td>
                                    <td>{{ $detail->user ? $detail->user->name : '' }}</td>
                                    <td>{{ $detail->percent }}</td>
                                    <td>{{ App\Helpers\StrHelper::currency(intval($detail->profit), 'Rp') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <div class="card-footer">
                    {{ $finances->links() }}
                </div> --}}
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
