@extends('layouts.admin.main')

@section('title', 'Kategori')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Kategori
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    {{-- Tombol tambah --}}
                    @admin
                        <a href="{{ route('kategori.create') }}" class="btn btn-success">Tambah Kategori</a>
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Kategori</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagination as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('kategori.edit', $item) }}"
                                            class="mb-1 mr-1 btn btn-warning">Edit</a>

                                        {{-- Menggunakan form, karena method untuk menghapus adalah DELETE --}}
                                        {{-- cek kembali di routes untuk memastikan --}}
                                        <form action="{{ route('kategori.destroy', $item) }}" method="post"
                                            onsubmit="return confirm('Apakah anda yakin ??')" class="d-inline-block">
                                            {{-- Agar tidak expired ketika di submit --}}
                                            @csrf
                                            {{-- Agar sesuai dengan method DELETE --}}
                                            {{-- karena secara default form cuma support GET, POST --}}
                                            @method('DELETE')
                                            {{-- Tombol Delete --}}
                                            <button class="mb-1 mr-1 btn btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
