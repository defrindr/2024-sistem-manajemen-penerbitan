@extends('layouts.admin.main')

@section('title', 'Karya Terkait: ' . $theme->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Karya Terkait
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <a href="{{ url()->previous() }}" class="btn btn-default">
                        Kembali
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>#</th>
                            {{-- <th>Topik</th> --}}
                            <th>Judul</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                            @if (count($theme->ebooks) == 0)
                                <tr>
                                    <td colspan="8" class="text-center">Belum terdapat karya yang diajukan</td>
                                </tr>
                            @else
                                @foreach ($theme->ebooks as $index => $ebook)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        {{-- <td>{{ $ebook->theme->name }}</td> --}}
                                        <td>{{ $ebook->title }}</td>
                                        <td>
                                            {{ $ebook->status }}
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
