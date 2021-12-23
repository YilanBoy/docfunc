<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>刪除帳號</title>

  {{-- Styles --}}
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  {{-- Font --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">
</head>

<body class="flex items-center justify-center min-h-screen antialiased bg-gray-100 font-noto">
  <x-card>
    <h3 class="pb-3 mb-3 text-lg font-semibold text-center border-b-2 border-black">
      <i class="bi bi-person-x-fill"></i><span class="ml-2">刪除帳號</span>
    </h3>

    <div class="flex flex-col">
      <span>如果您確定要刪除帳號，請點選下方的按鈕連結（連結將在 5 分鐘後失效）</span>
      <span class="mt-4 text-red-400">請注意！您撰寫的文章與留言都會一起刪除，而且無法恢復</span>
    </div>

    <div class="flex items-center justify-center mt-4">
      {{-- Delete User Button --}}
      <a
        href="{{ $destroyLink }}"
        onClick="return confirm('您確定要刪除帳號嗎？此動作無法復原');"
        class="inline-flex items-center justify-center px-4 py-2 font-semibold tracking-widest uppercase transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md text-gray-50 hover:bg-red-500 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25"
      >
        <i class="bi bi-exclamation-triangle-fill"></i><span class="ml-2">確認刪除帳號</span>
      </a>
    </div>
  </x-card>
</body>

</html>
