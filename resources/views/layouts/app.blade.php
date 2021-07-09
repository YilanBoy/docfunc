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

    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/algolia.css') }}" rel="stylesheet">
    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">

    @yield('css')

    @livewireStyles

    @yield('scriptsInHead')
</head>

<body class="flex flex-col min-h-screen justify-between bg-gray-100 antialiased font-noto">
    @include('layouts.header')

    @yield('content')

    @include('layouts.footer')

    {{-- Scripts --}}
    @livewireScripts
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- Include AlgoliaSearch JS Client and autocomplete.js library --}}
    <script>
        const algoliaId = "{{ config('scout.algolia.id') }}";
        const algoliaSearchKey = "{{ Algolia\ScoutExtended\Facades\Algolia::searchKey(App\Models\Post::class) }}";
        const algoliaIndex = "{{ config('scout.prefix') }}";
    </script>
    <script src="{{ asset('js/algolia.js') }}"></script>

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
</body>

</html>
