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
<body class="bg-gray-100 min-h-screen flex justify-center items-center antialiased font-noto">
    <x-card class="text-black">
        <h3 class="font-semibold text-lg text-center border-black border-b-2 pb-3 mb-3">
            <i class="bi bi-person-x-fill"></i><span class="ml-2">刪除帳號</span>
        </h3>

        <div class="flex flex-col">
            <span>如果您確定要刪除帳號，請點選下方的按鈕連結</span>
            <span class="text-red-400 mt-4">請注意！您撰寫的文章與留言都會一起刪除，而且無法恢復</span>
        </div>

        <form method="POST" action="{{ $deleteAccountLink }}" onSubmit="return confirm('您確定要刪除帳號嗎？此動作無法復原');" class="w-full">
            @method('DELETE')
            @csrf

            <div class="flex justify-center items-center mt-4">
                {{-- Delete User Button --}}
                <button
                    type="submit"
                    class="inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white
                    uppercase tracking-widest hover:bg-red-500 active:bg-red-900 focus:outline-none focus:border-red-900
                    focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150"
                >
                    <i class="bi bi-exclamation-triangle-fill"></i><span class="ml-2">確認刪除帳號</span>
                </button>
            </div>
        </form>
    </x-card>
</body>
</html>
