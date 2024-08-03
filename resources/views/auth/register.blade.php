@extends('layouts.auth.main')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}">
                {{ config('app.name') }}
            </a>
        </div> <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Register to access application</p>
                <form action="{{ route('register.action') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="input-group has-validation">
                            <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror"
                                placeholder="Name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group has-validation">
                            <input type="email" name="email" class="form-control  @error('email') is-invalid @enderror"
                                placeholder="Email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group has-validation">
                            <input type="text" name="phone" class="form-control  @error('phone') is-invalid @enderror"
                                placeholder="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group has-validation">
                            <input type="text" name="npwp" class="form-control  @error('npwp') is-invalid @enderror"
                                placeholder="npwp" value="{{ old('npwp') }}">
                            @error('npwp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group has-validation">
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                                value="{{ old('password') }}">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group has-validation">
                            <select name="roleId" id="roleId"
                                class="form-control @error('password') is-invalid @enderror">
                                <option value="">-- Pilih Hak Akses --</option>
                                @foreach ($roles as $item)
                                    <option value="{{ $item->id }}" @if (old('roleId') == $item->id) selected @endif>
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('roleId')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Sign Up</button>
                            </div>
                        </div> <!-- /.col -->
                    </div> <!--end::Row-->

                    <a href="{{ route('login') }}" class="btn btn-default">
                        Sign In
                    </a>
                </form>
            </div> <!-- /.login-card-body -->
        </div>
    </div> <!-- /.login-box -->
@endsection
