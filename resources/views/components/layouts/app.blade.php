<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

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
    content="{{ $description ?? config('app.name') }}"
  >

  <x-sharing-meta-tags />

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
  {{-- Styles --}}
  @vite('resources/css/app.css')
  @vite('resources/css/icon.css')

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

  {{-- jetbrains mono font --}}
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
    href="https://fonts.googleapis.com/css2?family=JetBrains+Mono&display=swap"
    rel="stylesheet"
  >

  <x-js.alpine-js />
  <x-js.livewire />

  {{-- Ckeditor --}}
  <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
  {{-- Tagify --}}
  @vite('resources/ts/tagify.ts')
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
  @vite('resources/ts/oembed/twitter-widgets.ts')
  @vite('resources/ts/oembed/oembed-media-embed.ts')

  {{-- google recaptcha --}}
  <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
</head>

<body
  class="overscroll-y-none bg-gray-200 font-sans text-lg text-gray-900 antialiased dark:bg-gray-900"
  x-data
  @scroll-to-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })"
  @hello-world.window="console.log('Hello World')"
>
  <div
    class="relative flex min-h-screen flex-col justify-between selection:bg-green-300 selection:text-green-900 dark:selection:bg-indigo-300 dark:selection:text-indigo-900"
  >
    <livewire:layouts.header />

    {{ $slot }}

    <livewire:layouts.footer />
  </div>

  {{-- Scripts --}}
  @vite('resources/js/app.js')

  {{-- Flash Alert --}}
  @persist('alert')
    <x-alert />
  @endpersist
</body>

</html>
