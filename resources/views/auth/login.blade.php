@extends('layouts.adminlteuser')

@section('title', 'Inicio de sesión')
@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">
            <a href="/">
                <img src="/images/user-password-icon.png" alt="login">
            </a>
            <br>
            <br>
            <b>
                {{ __('Login') }}
            </b>
            <br>
            <a class="text-muted" href="/">
                {{config('app.name')}}
            </a>
        </p>
        <form method="POST" action="{{ route('login') }}">
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
            <div class="input-group mb-3">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary ml-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Login') }}
                    </button>
                </div>
            </div>
        </form>
        <p class="mt-3 mb-1">
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
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
