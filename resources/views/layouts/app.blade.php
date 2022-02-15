<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- CSRF Token --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', config('app.name'))</title>
  <meta name="description" content="@yield('description', config('app.name'))">

  {{-- Open Graph --}}
  <meta property="og:url" content="{{ url()->full() }}">
  <meta property="og:type" content="{{ request()->route()->getName() === 'posts.show' ? 'article' : 'website' }}">
  <meta property="og:title" content="@yield('title', config('app.name'))">
  <meta property="og:description" content="@yield('description', config('app.name'))">
  <meta property="og:image" content="https://recode-blog-files.s3.ap-northeast-2.amazonaws.com/share.jpg">

  {{-- Favicon --}}
  <link rel="shortcut icon" href="{{ asset('images/icon/icon.svg') }}" type="image/x-icon">
  {{-- Set theme --}}
  <script src="{{ asset('js/set-theme.js') }}"></script>
  {{-- Styles --}}
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  {{-- Icon --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  {{-- Font --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">

  @yield('css')

  <livewire:styles/>

  {{-- Head Scripts --}}
  @yield('scriptsInHead')
</head>

<body
  x-data
  @scroll-to-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })"
  class="antialiased text-gray-900 bg-gray-200 overscroll-y-none dark:bg-gray-800 font-noto"
>

<div class="relative flex flex-col justify-between min-h-screen">

  @include('layouts.header')

  {{ $slot }}

  @include('layouts.footer')

</div>

{{-- Scripts --}}
<livewire:scripts/>

<script src="{{ asset('js/app.js') }}"></script>

{{-- Flash Alert --}}
@if (session()->has('alert'))
  <script>
    const flashAlert = @json(session()->get('alert'));
  </script>

  <script src="{{ asset('js/sweet-alert.js') }}"></script>
@endif

@yield('scripts')
</body>

</html>
