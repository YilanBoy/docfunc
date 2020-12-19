@extends('layouts.app')

@section('title', '註冊')

@section('scriptsInHead')
    {{-- Google reCAPTCHA --}}
    {{-- async defer 同時使用會優先使用 async，當瀏覽器不支援 async 才會使用 defer --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
    <div class="container mb-5">
        <div class="row justify-content-md-center">
            <div class="col-12 col-xl-6">

                <div class="card shadow">
                    <h5 class="card-header py-3"><i class="fas fa-user-plus"></i> {{ __('Register') }}</h5>

                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <div class="w-75">

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    {{-- 會員名稱 --}}
                                    <div class="form-floating mb-3">
                                        <input class="form-control @error('name') is-invalid @enderror" id="floatingInput" placeholder="name"
                                        type="text" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus>
                                        <label for="floatingInput">{{ __('Name') }}（請使用英文、數字、橫槓和底線）</label>
                                    </div>

                                    @error('name')
                                        <div class="mb-3">
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        </div>
                                    @enderror

                                    {{-- Email --}}
                                    <div class="form-floating mb-3">
                                        <input class="form-control @error('email') is-invalid @enderror" id="floatingInput" placeholder="email"
                                        type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
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
                                        <label for="floatingInput">{{ __('Password') }}（最少 8 個字元）</label>
                                    </div>

                                    @error('password')
                                        <div class="mb-3">
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        </div>
                                    @enderror

                                    {{-- 確認密碼 --}}
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="floatingInput" placeholder="password_confirmation"
                                        type="password" name="password_confirmation" required>
                                        <label for="floatingInput">{{ __('Confirm Password') }}</label>
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

                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Register') }}
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
