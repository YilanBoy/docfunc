<!DOCTYPE html>
<html class="min-vh-100 position-relative" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'RECODE')</title>
    <meta name="description" content="@yield('description', 'Allen 的個人部落格')" />
    <link rel="shortcut icon" href="{{ asset('icon/icon.png') }}">

    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('css')

    @yield('scriptsInHead')
</head>

<body>
    @include('layouts.header')

    @yield('content')

    {{-- @include('layouts.footer') --}}

    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>
    @foreach (['warning', 'success', 'info'] as $msg)
        @if(session()->has($msg))
            <script>
                var sweetalert2Title = '{{ session()->get($msg) }}';
                var sweetalert2Icon = '{{ $msg }}';
            </script>
            <script src="{{ asset('js/sweet.alert.js') }}"></script>
        @endif
    @endforeach

    @yield('scripts')

</body>

</html>
