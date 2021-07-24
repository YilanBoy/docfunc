<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'RECODE')</title>

    <meta name="description" content="@yield('description', 'Allen 的個人部落格')">

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('images/icon/icon.png') }}" type="image/x-icon">
    {{-- Set theme --}}
    <script src="{{ asset('js/set-theme.js') }}"></script>
    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">

    @yield('css')

    @livewireStyles

    @yield('scriptsInHead')
</head>

<body class="bg-gray-100 dark:bg-gray-700 antialiased font-noto">

    <div class="relative min-h-screen flex">

        @include('layouts.sidebar')

        <div class="ml-0 sm:ml-20 w-full flex flex-col justify-between">
            @include('layouts.header')

            @yield('content')

            @include('layouts.footer')
        </div>
    </div>

    {{-- Scripts --}}
    @livewireScripts
    <script src="{{ asset('js/app.js') }}"></script>

    @foreach (['warning', 'success', 'info'] as $message)
        @if(session()->has($message))
            <script>
                const sweetalert2Title = '{{ session()->get($message) }}';
                const sweetalert2Icon = '{{ $message }}';
            </script>

            <script src="{{ asset('js/sweet-alert.js') }}"></script>
        @endif
    @endforeach

    @yield('scripts')

    <script src="{{ asset('js/theme-switch.js') }}"></script>
</body>

</html>
