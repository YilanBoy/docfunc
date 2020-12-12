@extends('layouts.app')

@section('title', '登入')

@section('scriptsInHead')
    {{-- Google reCAPTCHA --}}
    {{-- async defer 同時使用會優先使用 async，當瀏覽器不支援 async 才會使用 defer --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-md-6">

                @if (session('status'))
                    <div class="alert alert-success border border-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card shadow">
                    <h5 class="card-header">{{ __('Login') }}</h5>

                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <div class="col-md-8">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    {{-- E-mail --}}
                                    <div class="form-floating mb-3">
                                        <input class="form-control @error('email') is-invalid @enderror" id="floatingInput" placeholder="email"
                                        type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                                        <label for="floatingInput">{{ __('E-Mail Address') }}</label>
                                    </div>

                                    @error('email')
                                        <div class="mb-3">
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        </div>
                                    @enderror

                                    {{-- 密碼 --}}
                                    <div class="form-floating mb-3">
                                        <input class="form-control @error('password') is-invalid @enderror" id="floatingInput" placeholder="password"
                                        type="password" name="password" required>
                                        <label for="floatingInput">{{ __('Password') }}</label>
                                    </div>

                                    @error('password')
                                        <div class="mb-3">
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        </div>
                                    @enderror

                                    {{-- 記住我 --}}
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="remember" id="flexCheckDefault"
                                        {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>

                                    {{-- reCAPTCHA --}}
                                    <div class="mb-3">
                                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha_site_key') }}"></div>

                                        @error('g-recaptcha-response')
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary w-50">
                                            {{ __('Login') }}
                                        </button>

                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
