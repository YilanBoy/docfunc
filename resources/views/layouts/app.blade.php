<!DOCTYPE html>
<html class="min-vh-100" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'RECODE')</title>
    <meta name="description" content="@yield('description', 'Allen 的個人部落格')">
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('icon/icon.png') }}" type="image/x-icon">

    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/algolia.css') }}" rel="stylesheet">

    @yield('css')

    @livewireStyles

    @yield('scriptsInHead')
</head>

<body class="d-flex flex-column min-vh-100 bg-gradient-blue">
    @include('layouts.header')

    @yield('content')

    @include('layouts.footer')

    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- Include AlgoliaSearch JS Client v3 and autocomplete.js library --}}
    <script src="https://cdn.jsdelivr.net/npm/algoliasearch@3/dist/algoliasearchLite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
    <script>
        const client = algoliasearch(
            "{{ config('scout.algolia.id') }}",
            "{{ Algolia\ScoutExtended\Facades\Algolia::searchKey(App\Models\Post::class) }}"
        );
        const posts = client.initIndex("{{ config('scout.prefix') }}");
    </script>
    <script src="{{ asset('js/algolia.js') }}"></script>

    @livewireScripts

    @yield('scripts')

    @foreach (['warning', 'success', 'info'] as $msg)
        @if(session()->has($msg))
            <script>
                const sweetalert2Title = '{{ session()->get($msg) }}';
                const sweetalert2Icon = '{{ $msg }}';
            </script>
            <script src="{{ asset('js/sweet-alert.js') }}"></script>
        @endif
    @endforeach

</body>

</html>
