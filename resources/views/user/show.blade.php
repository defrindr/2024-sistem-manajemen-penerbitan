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
                    <a href="{{ route('user.index') }}" class="btn btn-danger">Kembali</a>
                    @admin
                        {{-- <a href="{{ route('user.create') }}" class="btn btn-success">Tambah Kategori</a> --}}
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>: {{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td>Hak Akses</td>
                                <td>: {{ $user->role->name }}</td>
                            </tr>
                            <tr>
                                <td>HP</td>
                                <td>: {{ $user->phone }}</td>
                            </tr>
                            <tr>
                                <td>Bio</td>
                                <td>: {{ $user->bio }}</td>
                            </tr>
                            <tr>
                                <td>NPWP</td>
                                <td>: {{ $user->npwp }}</td>
                            </tr>
                            {{-- <tr>
                                <td>KTP</td>
                                <td>: {{ asset("storage/". $user->ktp) }}</td>
                            </tr> --}}
                            <tr>
                                <td>Bank</td>
                                <td>: {{ $user->bank }}</td>
                            </tr>
                            <tr>
                                <td>No Rekening</td>
                                <td>: {{ $user->noRekening }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
