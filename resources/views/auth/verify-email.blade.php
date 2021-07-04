@extends('layouts.app')

@section('title', '驗證信箱')

@section('content')
    <main class="container mx-auto max-w-7xl">
        <div class="flex justify-center items-center px-4 xl:px-0">
            <div class="w-full lg:w-1/3 flex flex-col sm:justify-center items-center bg-gray-100 pb-12">

                {{-- Logo --}}
                <div class="fill-current text-gray-700 text-2xl">
                    <i class="bi bi-person-check-fill"></i><span class="ml-4">驗證 Email</span>
                </div>

                <div class="w-full sm:max-w-md mt-4 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <div class="mb-4 text-gray-600">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 font-medium text-green-600">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif

                    <div class="mt-4 flex items-center justify-between">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf

                            <div>
                                <x-button>
                                    {{ __('Resend Verification Email') }}
                                </x-button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="text-gray-600 hover:text-gray-900">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <div class="container mb-5">
        <div class="row justify-content-md-center">
            <div class="col-12 col-xl-6">

                <div class="card shadow">
                    <h5 class="card-header py-3">
                        <i class="fas fa-exclamation-triangle"></i> {{ __('Verify Your Email Address') }}
                    </h5>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <pre style="margin-bottom: 0;"> ____                                      </pre>
                        <pre style="margin-bottom: 0;">/ ___|  ___  _ __ _ __ _   _               </pre>
                        <pre style="margin-bottom: 0;">\___ \ / _ \| '__| '__| | | |              </pre>
                        <pre style="margin-bottom: 0;"> ___) | (_) | |  | |  | |_| |  _   _   _   </pre>
                        <pre style="margin-bottom: 0;">|____/ \___/|_|  |_|   \__, | (_) (_) (_)  </pre>
                        <pre style="margin-bottom: 0;">                       |___/               </pre>
                        <br>
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        <p></p>
                        {{ __('If you did not receive the email') }}，

                        <form class="d-inline-flex" method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 text-decoration-none">{{ __('Click here to request another') }}</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
