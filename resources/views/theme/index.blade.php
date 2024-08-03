@extends('layouts.admin.main')

@section('title', 'Daftar Topik')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Topik Cerita
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    {{-- Tombol tambah --}}
                    @admin(true)
                        <a href="{{ route('theme.create') }}" class="btn btn-success">Tambah Topik</a>
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Topik Cerita</td>
                                <td>Deskripsi</td>
                                <td>Biaya Pendaftaran</td>
                                <td>Status</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagination as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->priceFormatted }}</td>
                                    <td>{{ $item->statusFormatted }}</td>
                                    <td>
                                        @adminreviewer(true)
                                            <a href="{{ route('theme.show', $item) }}" class="btn btn-info">Lihat</a>
                                        @endadminreviewer
                                        @admin(true)
                                            @if ($item->status == \App\Models\Theme::STATUS_REVIEW)
                                                {{-- cek kembali di routes untuk memastikan --}}
                                                <form action="{{ route('theme.close', $item) }}" method="post"
                                                    onsubmit="return confirm('Apakah anda yakin ??')" class="d-inline-block">
                                                    {{-- Agar tidak expired ketika di submit --}}
                                                    @csrf
                                                    {{-- Tombol Delete --}}
                                                    <button class="btn btn-primary bg-purple">Tutup</button>
                                                </form>
                                            @elseif ($item->status == \App\Models\Theme::STATUS_OPEN)
                                                {{-- cek kembali di routes untuk memastikan --}}
                                                <form action="{{ route('theme.review', $item) }}" method="post"
                                                    onsubmit="return confirm('Apakah anda yakin ??')" class="d-inline-block">
                                                    {{-- Agar tidak expired ketika di submit --}}
                                                    @csrf
                                                    {{-- Tombol Delete --}}
                                                    <button class="btn btn-primary bg-purple">Ke Tahap Review</button>
                                                </form>
                                            @else
                                                {{-- cek kembali di routes untuk memastikan --}}
                                                <form action="{{ route('theme.open', $item) }}" method="post"
                                                    onsubmit="return confirm('Apakah anda yakin ??')" class="d-inline-block">
                                                    {{-- Agar tidak expired ketika di submit --}}
                                                    @csrf
                                                    {{-- Tombol Delete --}}
                                                    <button class="btn btn-primary bg-purple">Buka Pengajuan</button>
                                                </form>

                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('theme.edit', $item) }}" class="btn btn-warning">Edit</a>

                                                {{-- Menggunakan form, karena method untuk menghapus adalah DELETE --}}
                                                {{-- cek kembali di routes untuk memastikan --}}
                                                <form action="{{ route('theme.destroy', $item) }}" method="post"
                                                    onsubmit="return confirm('Apakah anda yakin ??')" class="d-inline-block">
                                                    {{-- Agar tidak expired ketika di submit --}}
                                                    @csrf
                                                    {{-- Agar sesuai dengan method DELETE --}}
                                                    {{-- karena secara default form cuma support GET, POST --}}
                                                    @method('DELETE')
                                                    {{-- Tombol Delete --}}
                                                    <button class="btn btn-danger">Hapus</button>
                                                </form>
                                            @endif
                                        @endadmin
                                        @author(true)
                                        {{-- Tombol Tambah Ebook --}}

                                        @if (!$item->doesntHaveEbook() && $item->status == \App\Models\Theme::STATUS_OPEN)
                                            <a href="{{ route('ebook.create', $item) }}" class="btn btn-primary">
                                                Daftar Ke Topik
                                            </a>
                                        @endif
                                        @endauthor
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
