@section('title', '會員中心-刪除帳號')

<div class="container mx-auto max-w-7xl flex-1">
  <div class="flex flex-col items-start justify-center px-4 md:flex-row xl:px-0">
    <livewire:users.edit.side-menu />

    <x-card class="mt-6 flex w-full flex-col justify-center space-y-6 md:mt-0 md:w-1/2">
      {{-- 說明 --}}
      <div class="flex flex-col items-start justify-center">
        <span class="dark:text-gray-50">很遺憾您要離開...</span>
        <span class="dark:text-gray-50">如果您確定要刪除帳號，請點選下方的按鈕並收取信件</span>
      </div>

      <div class="mt-4 border-l-4 border-red-400 bg-red-200 px-4 py-2 text-gray-700">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span class="ml-2">請注意！您撰寫的文章與留言都會一起刪除，而且無法恢復！</span>
      </div>

      {{-- 寄出刪除帳號信件 --}}
      <div class="w-full">
        <button
          onclick="confirm('您確定要寄出刪除帳號信件嗎？') || event.stopImmediatePropagation()"
          wire:click="sendDestroyEmail"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 font-semibold uppercase tracking-widest text-gray-50 ring-red-300 transition duration-150 ease-in-out hover:bg-red-700 focus:border-red-900 focus:outline-none focus:ring active:bg-red-900 disabled:opacity-25"
        >
          寄出刪除帳號信件
        </button>
      </div>
    </x-card>
  </div>
</div>
