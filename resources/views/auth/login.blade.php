@extends('layouts.login')

@section('content')

<div class="row no-gutters">
    <div class="col-sm-6">
        <div class="ex-login-left">
            <a class="ex-login-logo" href="#"><img src="{{ asset('img/login_logo.png') }}"></a>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="ex-login-right">
            <h1>Welcome <span>Suppliers</span></h1>
            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}"  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="input-icon">
                        <i><img src="{{ asset('img/mail.png') }}"></i>
                        <input id="username" type="text" class="form-control  login-input{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" placeholder="Username" required autofocus>
                        @if ($errors->has('username'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-icon">
                        <i><img src="{{ asset('img/password.png') }}"></i>              
                        <input id="password" placeholder="Password" type="password" class="form-control login-input{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                        @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="login-btn">
                        {{ __('Login') }}
                    </button>

                    <!--<a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>-->
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
