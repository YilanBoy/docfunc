<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- CSRF Token --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', config('app.name'))</title>

  {{-- Primary Meta Tags--}}
  <meta name="title" content="@yield('title', config('app.name'))">
  <meta name="description" content="@yield('description', config('app.name'))">

  {{-- Open Graph / Facebook --}}
  <meta property="og:url" content="{{ url()->full() }}">
  <meta property="og:type" content="{{ request()->route()->getName() === 'posts.show' ? 'article' : 'website' }}">
  <meta property="og:title" content="@yield('title', config('app.name'))">
  <meta property="og:description" content="@yield('description', config('app.name'))">
  <meta property="og:image"
        content="@yield('preview_url', 'https://blog-archives.s3.ap-northeast-2.amazonaws.com/share.jpg')">

  {{-- Twitter--}}
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="{{ url()->full() }}">
  <meta property="twitter:title" content="@yield('title', config('app.name'))">
  <meta property="twitter:description" content="@yield('description', config('app.name'))">
  <meta property="twitter:image"
        content="@yield('preview_url', 'https://blog-archives.s3.ap-northeast-2.amazonaws.com/share.jpg')">

  {{-- Ｗeb Feed --}}
  @include('feed::links')

  {{-- Favicon --}}
  <link rel="shortcut icon" href="{{ asset('images/icon/icon.svg') }}" type="image/x-icon">
  {{-- Set theme --}}
  <script>
    if (
      localStorage.mode === "light" ||
      (!("mode" in localStorage) &&
        window.matchMedia("(prefers-color-scheme: light)").matches)
    ) {
      document.documentElement.classList.remove("dark");
    } else {
      document.documentElement.classList.add("dark");
    }
  </script>
  {{-- Styles --}}
  @vite('resources/css/app.css')
  {{-- Icon --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  {{-- Font --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">

  @yield('css')

  <livewire:styles/>

  @stack('scriptInHead')
</head>

<body
  x-data="alertComponent(@js(session()->get('alert')))"
  x-init="
    if (alert !== null) {
      showAlert(alert.status, alert.message)

      setTimeout(function () {
        openAlertBox = false
      }, 3000);
    }
  "
  {{-- when change page, scroll to top --}}
  @scroll-to-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })"
  class="antialiased text-gray-900 bg-gray-200 overscroll-y-none dark:bg-gray-800 font-noto text-lg"
>

<div
  class="relative flex flex-col justify-between min-h-screen selection:bg-green-300 selection:text-green-900 dark:selection:bg-purple-300 dark:selection:text-purple-900">

  <livewire:layouts.header/>

  {{ $slot }}

  <livewire:layouts.footer/>

</div>

{{-- Scripts --}}
<livewire:scripts/>

@vite('resources/js/app.js')

{{-- Flash Alert --}}
<x-alert/>

@stack('script')
</body>

</html>
