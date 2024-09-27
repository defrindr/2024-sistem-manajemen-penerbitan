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
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="{{ route('login.action') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend3">@</span>
                            <input type="text" name="email" class="form-control  @error('email') is-invalid @enderror"
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
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div> <!--begin::Row-->
                    <div class="row">
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Remember Me
                                </label>
                            </div>
                        </div> <!-- /.col -->
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Sign In</button>
                            </div>
                        </div> <!-- /.col -->

                        <a href="{{ route('register') }}" class="btn btn-default">
                            Sign Up
                        </a>
                    </div> <!--end::Row-->
                </form>
            </div> <!-- /.login-card-body -->
        </div>
    </div> <!-- /.login-box -->
@endsection
