@extends('users.index')

@section('title', '會員中心-刪除帳號')

@section('users.content')
    {{-- 說明 --}}
    <div class="flex flex-col justify-center items-start mb-4">
        <span class="text-black dark:text-white">很遺憾您要離開...</span>
        <span class="text-black dark:text-white">如果您確定要刪除帳號，請點選下方的按鈕並收取信件</span>
        <span class="text-red-400 mt-4">請注意！您撰寫的文章與留言都會一起刪除，而且無法恢復</span>
    </div>

    {{-- Session 狀態訊息 --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('users.sendDestroyEmail', ['user' => $user->id]) }}" onSubmit="return confirm('您確定要寄出刪除帳號信件嗎？');" class="w-full">
        @csrf

        <div class="flex items-center justify-end mt-4">
            {{-- 寄出刪除帳號信件 --}}
            <button
                type="submit"
                class="inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white
                uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900
                focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150"
            >
                <i class="bi bi-exclamation-triangle-fill"></i><span class="ml-2">寄出刪除帳號信件</span>
            </button>
        </div>
    </form>
@endsection
