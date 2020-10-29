@extends('layouts.adminlteuser')

@section('title', 'Restablecer contraseña')
@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">
            <a href="/">
                <img src="/images/secrecy-icon.png" alt="login">
            </a>
            <br>
            <br>
            <b>
                {{ __('Reset Password') }}
            </b>
            <br>
            <a class="text-muted" href="/">
                {{config('app.name')}}
            </a>
        </p>
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="input-group mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('E-Mail Address') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
            </div>
        </form>
        <p class="mt-3 mb-1">
            @if (Route::has('login'))
            <a href="{{ route('login') }}">{{ __('Login') }}</a>
            @endif
        </p>
        <p class="mb-0">
            @if (Route::has('register'))
            <a href="{{ route('register') }}">{{ __('Register') }}</a>
            @endif
        </p>
    </div>
</div>
@endsection
