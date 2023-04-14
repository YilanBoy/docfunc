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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">
</head>

<body class="flex items-center justify-center min-h-screen antialiased bg-gray-100 font-noto">
  {{ $slot }}
</body>

</html>