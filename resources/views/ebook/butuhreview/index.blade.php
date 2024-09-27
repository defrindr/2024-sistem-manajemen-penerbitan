@extends('layouts.admin.main')

@section('title', 'Butuh Review')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Butuh Review
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
                            <th>Tanggal Pengajuan</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @if ($pagination->count() <= 0)
                                <tr>
                                    <td colspan="6" class="text-center">Belum terdapat karya yang diajukan</td>
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
                                        <td>{{ $ebook->createdAtFormatted }}</td>
                                        <td>
                                            <a href="{{ route('ebook.butuhreview.view', $ebook) }}" class="btn btn-warning">
                                                Edit
                                            </a>
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
