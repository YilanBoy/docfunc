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
    <link rel="shortcut icon" href="{{ asset('icon/icon.png') }}" type="image/x-icon">

    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/algolia.css') }}" rel="stylesheet">

    @yield('css')

    @livewireStyles

    @yield('scriptsInHead')
</head>

<body class="bg-gradient-to-br from-green-400 to-blue-400 text-gray-900 antialiased">
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
