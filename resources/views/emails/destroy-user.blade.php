<x-mail-base :title="'DocFunc - 帳號刪除確認'">
  <x-card x-data>
    <h3 class="mb-3 border-b-2 border-black pb-3 text-center text-lg font-semibold">
      <span class="ml-2">DocFunc - 帳號刪除確認</span>
    </h3>

    <div class="flex flex-col">
      <span>如果您確定要刪除帳號，請點選下方的按鈕連結（連結將在 5 分鐘後失效）</span>
      <span class="mt-4 text-red-400">請注意！您撰寫的文章與留言都會一起刪除，而且無法恢復！</span>
    </div>

    <div class="mt-4 flex items-center justify-center">
      {{-- Delete User Button --}}
      <a
        class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 font-semibold uppercase tracking-widest text-gray-50 ring-red-300 transition duration-150 ease-in-out hover:bg-red-500 focus:border-red-900 focus:outline-none focus:ring active:bg-red-900 disabled:opacity-25"
        href="{{ $destroyLink }}"
        x-on:click.prevent="
          if (confirm('您確定要刪除帳號嗎？此動作無法復原')) {
            $el.click()
          }
        "
      >
        <span class="ml-2">確認刪除帳號</span>
      </a>
    </div>
  </x-card>
</x-mail-base>
