@extends('layouts.app')

@section('title', '驗證信箱')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <pre style="margin-bottom: 0;"> ___  ___  _ __ _ __ _   _                 </pre>
                        <pre style="margin-bottom: 0;">/ __|/ _ \| '__| '__| | | |                </pre>
                        <pre style="margin-bottom: 0;">\__ \ (_) | |  | |  | |_| |  _   _   _     </pre>
                        <pre style="margin-bottom: 0;">|___/\___/|_|  |_|   \__, | (_) (_) (_)    </pre>
                        <pre style="margin-bottom: 0;">                     |___/                 </pre>
                        <br>
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        <p></p>
                        {{ __('If you did not receive the email') }}，
                        <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
