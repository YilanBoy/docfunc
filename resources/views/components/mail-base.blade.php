<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">

  @if ($attributes->has('title'))
    <title>{{ $title }}</title>
  @else
    <title>DocFunc</title>
  @endif

  {{-- Styles --}}
  @vite(['resources/css/app.css'])
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
</head>

<body class="font-noto flex min-h-screen items-center justify-center bg-gray-100 antialiased">
  {{ $slot }}
</body>

</html>
