@section('title', '會員中心-編輯個人資料')

<x-card class="mt-6 flex w-full flex-col justify-center space-y-6 md:mt-0 md:w-1/2">
  <div class="flex flex-col items-center justify-center">
    {{-- 大頭貼照片 --}}
    <div>
      <img
        class="h-36 w-36 rounded-full"
        src="{{ $user->gravatar_url }}"
        alt="{{ $name }}"
      >
    </div>

    <div class="mt-4 flex dark:text-gray-50">
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
  <x-auth-validation-errors :errors="$errors" />

  <form
    wire:submit.prevent="update"
    class="w-full"
  >
    {{-- 信箱 --}}
    <div>
      <label
        for="email"
        class="text-gray-600 dark:text-gray-50"
      >信箱</label>

      @php
        $emailLength = strlen($user->email);
        $startToMask = round($emailLength / 4);
        $maskLength = ceil($emailLength / 2);
      @endphp

      <input
        id="email"
        type="text"
        name="email"
        value="{{ str()->mask($user->email, '*', $startToMask, $maskLength) }}"
        placeholder="信箱"
        disabled
        class="mt-2 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 disabled:border-slate-200 disabled:bg-slate-50 disabled:text-slate-500 disabled:shadow-none dark:disabled:border-slate-500 dark:disabled:bg-slate-700 dark:disabled:text-slate-400"
      >
    </div>

    {{-- 會員名稱 --}}
    <div class="mt-6">
      <label
        for="name"
        class="text-gray-600 dark:text-gray-50"
      >會員名稱 (只能使用英文、數字、_ 或是 -)</label>

      <input
        wire:model.lazy="name"
        id="name"
        type="text"
        name="name"
        value="{{ old('name', $name) }}"
        placeholder="給自己取個有趣的暱稱吧！"
        required
        autofocus
        class="form-input mt-2 w-full rounded-md border border-gray-300 text-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50 dark:placeholder-white"
      >
    </div>

    {{-- 會員自介 --}}
    <div class="mt-6">
      <label
        for="introduction"
        class="text-gray-600 dark:text-gray-50"
      >個人介紹 (最多 80 個字)</label>

      <textarea
        wire:model.lazy="introduction"
        id="introduction"
        name="introduction"
        placeholder="介紹一下你自己吧！"
        rows="5"
        class="form-textarea mt-2 w-full resize-none rounded-md border border-gray-300 text-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50 dark:placeholder-white"
      >{{ old('introduction', $introduction) }}</textarea>
    </div>

    <div class="mt-6 flex items-center justify-end">
      {{-- 儲存按鈕 --}}
      <x-button>
        <i class="bi bi-save2-fill"></i><span class="ml-2">儲存</span>
      </x-button>
    </div>
  </form>
</x-card>
