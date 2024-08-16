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
    </div>
@endsection
