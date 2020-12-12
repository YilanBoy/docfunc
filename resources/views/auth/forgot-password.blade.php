@extends('layouts.app')

@section('title', '忘記密碼')

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
                    <h5 class="card-header py-3">{{ __('Forgot Password') }}</h5>

                    <div class="d-flex justify-content-center">
                        <div class="col-md-8">

                            <div class="card-body">
                                <form method="POST" action="{{ route('password.email') }}">
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

                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Send Password Reset Link') }}
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
