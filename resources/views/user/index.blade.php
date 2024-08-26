@extends('layouts.admin.main')

@section('title', 'Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Pengguna
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    {{-- Tombol tambah --}}
                    @admin
                        {{-- <a href="{{ route('user.create') }}" class="btn btn-success">Tambah Kategori</a> --}}
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Nama</td>
                                <td>Email</td>
                                <td>No HP</td>
                                <td>Hak Akses</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagination as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->role->name }}</td>
                                    <td>
                                        <a href="{{ route('user.show', $item) }}" class="btn btn-primary">Show</a>
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('user.edit', $item) }}" class="btn btn-warning">Edit</a>

                                        {{-- Menggunakan form, karena method untuk menghapus adalah DELETE --}}
                                        {{-- cek kembali di routes untuk memastikan --}}
                                        {{-- <form action="{{ route('user.destroy', $item) }}" method="post"
                                            onsubmit="return confirm('Apakah anda yakin ??')" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger">Hapus</button>
                                        </form> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            {{ $pagination->links() }}
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
