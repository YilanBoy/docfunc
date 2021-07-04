<!DOCTYPE html>
<html class="min-vh-100" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>頁面不存在</title>
    <meta name="description" content="頁面不存在">
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('images/icon/icon.png') }}" type="image/x-icon">

    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body class="d-flex justify-content-center align-items-center min-vh-100 bg-gradient-blue">

    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12 col-xl-6">

                <div class="card shadow">
                    <h5 class="card-header py-3">
                        <i class="fas fa-exclamation-triangle"></i> {{ __('Not Found') }}
                    </h5>

                    <div class="card-body">
                        <pre style="margin-bottom: 0;">  ___                  _                   </pre>
                        <pre style="margin-bottom: 0;"> / _ \  ___  _ __  ___| |                  </pre>
                        <pre style="margin-bottom: 0;">| | | |/ _ \| '_ \/ __| |                  </pre>
                        <pre style="margin-bottom: 0;">| |_| | (_) | |_) \__ \_|                  </pre>
                        <pre style="margin-bottom: 0;"> \___/ \___/| .__/|___(_)                  </pre>
                        <pre style="margin-bottom: 0;">            |_|                            </pre>

                        <br>
                        糟糕！此頁面並不存在，可以從這裡
                        <a href="{{ route('root') }}" class="link-primary text-decoration-none">{{ __('Back To Homepage')}}</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>

