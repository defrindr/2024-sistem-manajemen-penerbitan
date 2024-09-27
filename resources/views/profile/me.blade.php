@extends('layouts.admin.main')

@section('title', 'Edit User')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        My Profile
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <form action="{{ route('profile.update-me') }}" method="POST" class="form" enctype="multipart/form-data"
                    onsubmit="return confirm('Apakah anda yakin ??')">
                    <div class="card-body">
                        {{-- Agar tidak 419 expired, ketika di simpan --}}
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="name">Nama Pengguna</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name" value="{{ old('name') ?? $user->name }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        name="email" id="email" value="{{ old('email') ?? $user->email }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" id="password" value="">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="phone">No HP</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" id="phone" value="{{ old('phone') ?? $user->phone }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="bio">BIO</label>
                                    <textarea name="bio" id="bio" cols="30" rows="10" class="form-control">{{ old('bio') ?? $user->bio }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="address">Alamat</label>
                                    <textarea name="address" id="address" rows="3" class="form-control">{{ old('address') ?? $user->address }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="npwp">NPWP</label>
                                    <input type="text" class="form-control @error('npwp') is-invalid @enderror"
                                        name="npwp" id="npwp" value="{{ old('npwp') ?? $user->npwp }}">
                                    @error('npwp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                @if ($user->ktp)
                                    <img src="{{ asset('storage/ktp/' . $user->ktp) }}" class="img-fluid" alt="Image">
                                @endif
                                <div class="form-group">
                                    <label for="ktp">KTP</label>
                                    <input type="file" class="form-control @error('ktp') is-invalid @enderror"
                                        name="ktp" id="ktp">
                                    @error('ktp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                @if ($user->ttd)
                                    <img src="{{ asset('storage/ttd/' . $user->ttd) }}" class="img-fluid" alt="Image">
                                @endif
                                <div class="form-group">
                                    <label for="ttd">TTD</label>
                                    <input type="file" class="form-control @error('ttd') is-invalid @enderror"
                                        name="ttd" id="ttd">
                                    @error('ttd')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="bank">Bank</label>
                                    <input type="text" class="form-control @error('bank') is-invalid @enderror"
                                        name="bank" id="bank" value="{{ old('bank') ?? $user->bank }}">
                                    @error('bank')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="noRekening">No Rekening</label>
                                    <input type="text" class="form-control @error('noRekening') is-invalid @enderror"
                                        name="noRekening" id="noRekening"
                                        value="{{ old('noRekening') ?? $user->noRekening }}">
                                    @error('noRekening')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
