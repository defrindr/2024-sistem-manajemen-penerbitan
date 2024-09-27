@extends('layouts.admin.main')

@section('title', 'Daftar Siap Publish')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Daftar Siap Publish
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>#</th>
                            <th>Topik</th>
                            <th>Judul</th>
                            <th>Draft</th>
                            <th>Status</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @if ($pagination->count() <= 0)
                                <tr>
                                    <td colspan="7" class="text-center">Belum terdapat karya yang diajukan</td>
                                </tr>
                            @else
                                @foreach ($pagination->items() as $index => $ebook)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $ebook->theme->name }}</td>
                                        <td>{{ $ebook->title }}</td>
                                        <td>
                                            <a href="{{ $ebook->draftPath }}" target="_blank" rel="noopener noreferrer">
                                                Buka
                                            </a>
                                        </td>
                                        <td>{{ $ebook->status }}</td>
                                        <td>{{ $ebook->createdAtFormatted }}</td>
                                        <td>
                                            <form action="{{ route('ebook.publish', $ebook) }}" method="post"
                                                onsubmit="return confirm('Apakah anda yakin ??')" class="d-inline-block">
                                                {{-- Agar tidak expired ketika di submit --}}
                                                @csrf
                                                {{-- Tombol Delete --}}
                                                <button class="btn btn-primary">Publish</button>
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
