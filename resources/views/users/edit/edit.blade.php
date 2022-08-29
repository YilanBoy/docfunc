@extends('users.edit.index')

@section('title', '會員中心-編輯個人資料')

@section('users.content')
  <div class="flex flex-col items-center justify-center">
    {{-- 大頭貼照片 --}}
    <div>
      <img
        class="rounded-full h-36 w-36"
        src="{{ $user->gravatar_url }}"
        alt="{{ $user->name }}"
      >
    </div>

    <div class="flex mt-4 dark:text-gray-50">
      <span class="mr-2">會員大頭貼由</span>
      <a
        href="https://zh-tw.gravatar.com/"
        target="_blank"
        rel="nofollow noopener noreferrer"
        class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
      >Gravatar</a>
      <span class="ml-2">技術提供</span>
    </div>
  </div>

  {{-- 驗證錯誤訊息 --}}
  <x-auth-validation-errors :errors="$errors"/>

  <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}" class="w-full">
    @method('PUT')
    @csrf

    {{-- 信箱 --}}
    <div>
      <label
        for="email"
        class="text-gray-600 dark:text-gray-50"
      >信箱</label>

      <input
        id="name"
        type="text"
        name="email"
        value="{{ $user->email }}"
        placeholder="信箱"
        disabled
        class="mt-2 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md focus:ring-1 invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 disabled:shadow-none dark:disabled:bg-slate-700 dark:disabled:text-slate-400 dark:disabled:border-slate-500"
      >
    </div>

    {{-- 會員名稱 --}}
    <div class="mt-6">
      <label
        for="name"
        class="text-gray-600 dark:text-gray-50"
      >會員名稱 (只能使用英文、數字、_ 或是 -)</label>

      <input
        id="name"
        type="text"
        name="name"
        value="{{ old('name', $user->name) }}"
        placeholder="給自己取個有趣的暱稱吧！"
        required
        autofocus
        class="w-full mt-2 border border-gray-300 rounded-md shadow-sm form-input text-lg focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50 dark:placeholder-white"
      >
    </div>

    {{-- 會員自介 --}}
    <div class="mt-6">
      <label for="introduction" class="text-gray-600 dark:text-gray-50">個人介紹 (最多 80 個字)</label>

      <textarea
        id="introduction"
        name="introduction"
        placeholder="介紹一下你自己吧！"
        rows="5"
        class="w-full mt-2 border border-gray-300 rounded-md shadow-sm form-textarea text-lg focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50 dark:placeholder-white"
      >{{ old('introduction', $user->introduction) }}</textarea>
    </div>

    <div class="flex items-center justify-end mt-6">
      {{-- 儲存按鈕 --}}
      <x-button>
        <i class="bi bi-save2-fill"></i><span class="ml-2">儲存</span>
      </x-button>
    </div>
  </form>
@endsection
