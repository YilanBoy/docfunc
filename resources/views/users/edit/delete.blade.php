@extends('users.edit.index')

@section('title', '會員中心-刪除帳號')

@section('users.content')
  {{-- Session 狀態訊息 --}}
  <x-auth-session-status :status="session('status')"/>

  {{-- 說明 --}}
  <div class="flex flex-col items-start justify-center">
    <span class="dark:text-gray-50">很遺憾您要離開...</span>
    <span class="dark:text-gray-50">如果您確定要刪除帳號，請點選下方的按鈕並收取信件</span>
  </div>

  <div class="mt-4 text-gray-700 px-4 py-2 bg-red-200 border-l-4 border-red-400">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span class="ml-2">請注意！您撰寫的文章與留言都會一起刪除，而且無法恢復！</span>
  </div>

  <form
    x-data
    x-ref="sendDestroyEmail"
    method="POST"
    action="{{ route('users.sendDestroyEmail', ['user' => $user->id]) }}"
    class="w-full"
  >
    @csrf

    {{-- 寄出刪除帳號信件 --}}
    <button
      x-on:click.prevent="
          if (confirm('您確定要寄出刪除帳號信件嗎？')) {
            $refs.sendDestroyEmail.submit()
          }
        "
      type="submit"
      class="inline-flex items-center justify-center px-4 py-2 font-semibold tracking-widest uppercase transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md text-gray-50 hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25"
    >
      寄出刪除帳號信件
    </button>
  </form>
@endsection
