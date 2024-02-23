<!DOCTYPE html>
<html
  class="scroll-smooth"
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>

<head>
  {{-- prettier-ignore-start --}}
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- CSRF Token --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? config('app.name') }}</title>

  {{-- Primary Meta Tags --}}
  <meta name="title" content="{{ $title ?? config('app.name') }}">
  <meta name="description" content="@yield('description', config('app.name'))">
  <x-sharing-meta-tags :title="$title ?? config('app.name')" />

  {{-- Set theme --}}
  @vite('resources/ts/set-theme.ts')

  {{-- Ｗeb Feed --}}
  @include('feed::links')

  {{-- Favicon --}}
  <link rel="icon" href="{{ asset('images/icon/icon.png') }}" type="image/png">

  {{-- Font --}}
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono&display=swap" rel="stylesheet">

  @vite('resources/css/app.css')

  <x-js.livewire />
  @vite('resources/js/app.js')

  {{-- Cloudflare Turnstile --}}
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit"></script>
  {{-- prettier-ignore-end --}}
</head>

<body class="bg-dot overscroll-y-none bg-gray-200 font-sans text-lg text-gray-900 antialiased dark:bg-gray-900">

  {{ $slot }}

  {{-- Flash Alert --}}
  @persist('alert')
    <x-alert />
  @endpersist
</body>

</html>
