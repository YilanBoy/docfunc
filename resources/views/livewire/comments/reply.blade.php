<div
  x-data
  class="w-full"
>

  <div class="flex justify-between mt-6">
    {{-- show comments count --}}
    <span class="flex items-center dark:text-gray-50">
      <i class="bi bi-chat-square-text-fill"></i>
      <span class="ml-2">{{ $commentCount }} 則留言</span>
    </span>

    <button
      x-on:click="$dispatch('open-create-comment-modal')"
      type="button"
      class="group [transform:translateZ(0)] px-6 py-2 rounded-xl bg-emerald-500 overflow-hidden relative before:absolute before:bg-blue-600 before:bottom-0 before:left-0 before:h-full before:w-full before:origin-[100%_100%] before:scale-x-0 hover:before:origin-[0_0] hover:before:scale-x-100 before:transition before:ease-in-out before:duration-500"
    >
      <span class="relative z-0 text-lg font-semibold text-gray-200 transition duration-500 ease-in-out">
        <i class="bi bi-chat-dots-fill"></i><span class="ml-2">新增留言</span>
      </span>
    </button>
  </div>
</div>
