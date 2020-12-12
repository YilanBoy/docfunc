@extends('layouts.app')

@section('title', '重設密碼')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <h5 class="card-header py-3">{{ __('Reset Password') }}</h5>

                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <div class="col-md-8">
                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf

                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                    {{-- E-mail --}}
                                    <div class="mb-3">
                                        <input class="form-control"
                                        value="{{ $request->email ?? old('email') }}"
                                        type="email" name="email" required readonly>
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
                                        type="password" name="password" required autofocus>
                                        <label for="floatingInput">{{ __('Password') }}</label>
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

                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Reset Password') }}
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
