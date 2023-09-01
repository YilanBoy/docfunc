<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1"
  >
  {{-- CSRF Token --}}
  <meta
    name="csrf-token"
    content="{{ csrf_token() }}"
  >

  <title>{{ $title ?? config('app.name') }}</title>

  {{-- Primary Meta Tags --}}
  <meta
    name="title"
    content="{{ $title ?? config('app.name') }}"
  >
  <meta
    name="description"
    content="@yield('description', config('app.name'))"
  >

  <x-sharing-meta-tags :title="$title ?? config('app.name')" />

  {{-- Ｗeb Feed --}}
  @include('feed::links')

  {{-- Favicon --}}
  <link
    type="image/x-icon"
    href="{{ asset('images/icon/icon.svg') }}"
    rel="shortcut icon"
  >
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

  {{-- Font --}}
  <link
    href="https://fonts.googleapis.com"
    rel="preconnect"
  >
  <link
    href="https://fonts.gstatic.com"
    rel="preconnect"
    crossorigin
  >
  <link
    href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap"
    rel="stylesheet"
  >
  <link
    href="https://fonts.googleapis.com/css2?family=JetBrains+Mono&display=swap"
    rel="stylesheet"
  >

  @vite('resources/css/app.css')
  @vite('resources/css/icon.css')
  {{-- highlight code block style --}}
  @vite('node_modules/highlight.js/scss/base16/material-palenight.scss')
  {{-- editor style --}}
  @vite(['resources/css/editor.css', 'node_modules/@yaireo/tagify/dist/tagify.css', 'resources/css/missing-content-style.css'])

  <x-js.alpine-js />
  <x-js.livewire />
  @vite('resources/js/app.js')
  {{-- highlight code block --}}
  @vite('resources/ts/highlight.ts')
  {{-- code block copy button --}}
  @vite('resources/ts/copy-code-btn.ts')
  {{-- post read pregress bar --}}
  @vite('resources/ts/progress-bar.ts')
  {{-- to the top button --}}
  @vite('resources/ts/scroll-to-top-btn.ts')
  {{-- social media share button --}}
  @vite('resources/ts/sharer.ts')
  {{-- media embed --}}
  @vite('resources/ts/oembed/embed-youtube-oembed.ts')
  @vite('resources/ts/oembed/embed-twitter-oembed.ts')
  @vite('resources/ts/oembed/twitter-widgets.ts')
  {{-- Ckeditor --}}
  <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
  {{-- Tagify --}}
  @vite('resources/ts/tagify.ts')
</head>

<body class="overscroll-y-none bg-gray-200 font-sans text-lg text-gray-900 antialiased dark:bg-gray-900">
  <div
    class="relative flex min-h-screen flex-col justify-between selection:bg-green-300 selection:text-green-900 dark:selection:bg-indigo-300 dark:selection:text-indigo-900"
  >
    <livewire:shared.layouts.header />

    {{ $slot }}

    <livewire:shared.layouts.footer />
  </div>

  {{-- Flash Alert --}}
  @persist('alert')
    <x-alert />
  @endpersist
</body>

</html>
