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
  <link rel="shortcut icon" href="{{ asset('images/icon/icon.svg') }}" type="image/x-icon">
  {{-- Set theme --}}
  <script src="{{ asset('js/set-theme.js') }}"></script>
  {{-- Styles --}}
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  {{-- Icon --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  {{-- Font --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">

  @yield('css')

  <livewire:styles />

  {{-- Head Scripts --}}
  @yield('scriptsInHead')
</head>

<body class="antialiased text-gray-900 bg-gray-200 dark:bg-gray-800 font-noto">

  <div class="relative flex flex-col justify-between">

    @include('layouts.header')

    {{ $slot }}

    @include('layouts.footer')

  </div>

  {{-- Scripts --}}
  <livewire:scripts />

  <script src="{{ asset('js/app.js') }}"></script>

  @foreach (['warning', 'success', 'info'] as $message)
    @if (session()->has($message))
      <script>
        const sweetalert2Title = '{{ session()->get($message) }}';
        const sweetalert2Icon = '{{ $message }}';
      </script>

      <script src="{{ asset('js/sweet-alert.js') }}"></script>
    @endif
  @endforeach

  <script src="{{ asset('js/theme-switch.js') }}"></script>

  @yield('scripts')
</body>

</html>
