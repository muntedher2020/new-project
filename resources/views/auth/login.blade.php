@php
use Illuminate\Support\Facades\Route;

$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Login Cover - Pages')

@section('vendor-style')
@vite([
'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('page-style')
@vite([
'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('vendor-script')
@vite([
'resources/assets/vendor/libs/@form-validation/popular.js',
'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('page-script')
@vite([
'resources/assets/js/pages-auth.js'
])
@endsection

@section('content')
<div class="authentication-wrapper authentication-cover">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="auth-cover-brand d-flex align-items-center gap-2">
        <span class="app-brand-logo demo">@include('_partials.macros')</span>
        <span class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span>
    </a>
    <!-- /Logo -->
    <div class="authentication-inner row m-0">
        <!-- Login -->
        <div
            class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg position-relative py-sm-12 px-12 py-6">
            <div class="w-px-400 mx-auto pt-12 pt-lg-0">
                <h4 class="mb-1">Welcome to {{ config('variables.templateName') }}! 👋</h4>
                <p class="mb-3">Please sign-in to your account and start the adventure</p>

                <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-floating form-floating-outline mb-3 form-control-validation">
                        <input type="text" class="form-control" id="email" name="email"
                            placeholder="أدخل بريدك الإلكتروني" autofocus />
                        <label for="email">البريد الإلكتروني</label>
                    </div>
                    <div class="mb-3">
                        <div class="form-password-toggle form-control-validation">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <label for="password">كلمة المرور</label>
                                </div>
                                <span class="input-group-text cursor-pointer">
                                    <i class="icon-base ri ri-eye-off-line icon-20px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="mb-3 d-flex justify-content-between mt-3">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="remember-me" />
                            <label class="form-check-label" for="remember-me"> Remember Me </label>
                        </div>
                        <a href="{{ url('auth/forgot-password-cover') }}" class="float-end mb-1 mt-2">
                            <span>Forgot Password?</span>
                        </a>
                    </div> --}}
                    <button class="btn btn-primary waves-effect waves-light d-grid w-100 mb-3">تسجيل الدخول</button>
                    
                    {{-- <a href="{{ Route('User-Accounts.create') }}" class="btn btn-danger waves-effect waves-light d-grid w-100">تسجيل مستخدم جديد</a> --}}
                </form>

                {{-- <p class="text-center">
                    <span>New on our platform?</span>
                    <a href="{{ url('auth/register-cover') }}">
                        <span>Create an account</span>
                    </a>
                </p>

                <div class="divider my-5">
                    <div class="divider-text">or</div>
                </div> --}}

                {{-- <div class="d-flex justify-content-center gap-2">
                    <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-facebook">
                        <i class="icon-base ri  ri-facebook-fill icon-18px"></i>
                    </a>

                    <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-twitter">
                        <i class="icon-base ri  ri-twitter-fill icon-18px"></i>
                    </a>

                    <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-github">
                        <i class="icon-base ri  ri-github-fill icon-18px"></i>
                    </a>

                    <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-google-plus">
                        <i class="icon-base ri  ri-google-fill icon-18px"></i>
                    </a>
                </div> --}}
            </div>
        </div>
        <!-- /Login -->

        <!-- /Left Section -->
        <div
            class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center justify-content-center authentication-bg position-relative">
            <img src="{{ asset('assets/img/illustrations/auth-login-illustration-' . $configData['theme'] . '.png') }}"
                class="auth-cover-illustration w-100" alt="auth-illustration"
                data-app-light-img="illustrations/auth-login-illustration-light.png"
                data-app-dark-img="illustrations/auth-login-illustration-dark.png" />
            <img alt="mask"
                src="{{ asset('assets/img/illustrations/auth-basic-login-mask-' . $configData['theme'] . '.png') }}"
                class="authentication-image d-none d-lg-block"
                data-app-light-img="illustrations/auth-basic-login-mask-light.png"
                data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
        </div>
        <!-- /Left Section -->
    </div>
</div>
@endsection

{{-- @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address')}}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password')}}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{
                                        old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
