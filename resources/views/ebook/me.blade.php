@extends('layouts.admin.main')

@section('title', 'Karya Saya')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Karya Saya
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
                            <th>Royalti</th>
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
                                        <td>{{ $ebook->royalty }}</td>
                                        <td>{{ $ebook->createdAtFormatted }}</td>
                                        <td>
                                            @if ($ebook->status === \App\Models\Ebook::STATUS_SUBMIT)
                                                <a href="{{ route('ebook.edit', $ebook) }}" class="btn btn-warning">
                                                    Edit
                                                </a>
                                            @endif
                                            @if ($ebook->status === \App\Models\Ebook::STATUS_PUBLISH && $ebook->royalty == 0)
                                                <a href="{{ route('ebook.atur-royalti', $ebook) }}" class="btn btn-warning">
                                                    Atur Royalti
                                                </a>
                                            @endif
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
