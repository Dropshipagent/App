@extends('layouts.login')

@section('content')

<?php 
if (isset($_REQUEST['shop'])) {
    $shopDomainArr = explode(".", $_REQUEST['shop']);
    $shopDomain = $shopDomainArr[0];
} else {
    $shopDomain = '';
}
?>
<div class="row no-gutters">
    <div class="col-sm-6">
        <div class="ex-login-left">
            <a class="ex-login-logo" href="#"><img src="{{ asset('img/login_logo.png') }}"></a>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="ex-login-right">
            <div class="text-center">
                <h1>Create <span>New Account</span></h1>
            </div>
            <form method="GET" id="appInstallReq" action="{{ route('login.shopify') }}" aria-label="{{ __('Register') }}">
                @csrf
                <div class="form-group">
                    <div class="input-group mb-3">
                        <input id="domain" type="text" class="form-control login-input{{ $errors->has('domain') ? ' is-invalid' : '' }}" name="domain" value="{{ $shopDomain }}" placeholder="yourshop" aria-describedby="myshopify" required autofocus <?php echo ($shopDomain) ? 'readonly="readonly"' : '' ?>>
                        <div class="input-group-append">
                            <span class="input-group-text" id="myshopify">myshopify.com</span>
                        </div>
                    </div>

                </div>

                <div class="form-group text-center">
                    <button type="submit" class="login-btn">
                        {{ __('Continue') }}
                    </button>

                    <!--<a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>-->
                </div>
            </form>
            <div class="text-center mt-3">
                <p class="text-center text-muted">Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
