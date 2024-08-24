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
                    <a href="{{ url()->previous() }}" class="btn btn-danger">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="mb-5"
                        style="width: 100%;height: 750px;overflow: hidden;background: url('{{ $publication->coverLink }}') 100% 100%; background-position: center">
                    </div>
                    <table class="table table-hover table-striped">
                        <tbody>
                            <tr>
                                <td>ISBN</td>
                                <td>:</td>
                                <td>{{ $publication->theme->isbn }}</td>
                            </tr>
                            <tr>
                                <td>Judul</td>
                                <td>:</td>
                                <td>{{ $publication->theme->name }}</td>
                            </tr>
                            <tr>
                                <td>Nama Penulis</td>
                                <td>:</td>
                                <td>{{ $publication->theme->authors }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <h4>Sinopsis</h4>
                    <p>
                        {{ $publication->theme->description }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="card card-default">
                <div class="card-header">
                    <h3>Sub Tema</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>#</th>
                            <th>Sub Tema</th>
                            <th>Author</th>
                        </thead>
                        <tbody>
                            @if ($theme->subThemes()->count() == false)
                                <tr>
                                    <td colspan="7" class="text-center">Belum terdapat sub tema yang diajukan</td>
                                </tr>
                            @else
                                @foreach ($theme->subThemes as $index => $subTheme)
                                    <tr @if (request()->get('highlight') == $subTheme->id) class="bg-red" @endif>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $subTheme->name }}</td>
                                        <td>
                                            @if ($subTheme->hasAuthorRegistered())
                                                {{ $subTheme->ebook()->first()?->author?->name }} <br>
                                                Email: {{ $subTheme->ebook()->first()?->author?->email }} <br>
                                                HP: {{ $subTheme->ebook()->first()?->author?->phone }}
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
