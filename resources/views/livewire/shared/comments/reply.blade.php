<div class="mt-6 w-full">
  <div class="flex justify-between">
    {{-- show comments count --}}
    <span class="flex items-center dark:text-gray-50">
      <i class="bi bi-chat-square-text-fill"></i>
      <span class="ml-2">{{ $commentCounts }} 則留言</span>
    </span>

    <button
      class="group relative overflow-hidden rounded-xl bg-emerald-600 px-6 py-2 [transform:translateZ(0)] before:absolute before:bottom-0 before:left-0 before:h-full before:w-full before:origin-[100%_100%] before:scale-x-0 before:bg-blue-600 before:transition before:duration-500 before:ease-in-out hover:before:origin-[0_0] hover:before:scale-x-100"
      type="button"
      wire:click="$dispatch('open-create-comment-modal')"
    >
      <span class="relative z-0 text-lg font-semibold text-gray-200 transition duration-500 ease-in-out">
        <i class="bi bi-chat-dots-fill"></i>
        <span class="ml-2">
          @if (auth()->check())
            新增留言
          @else
            訪客留言
          @endif
        </span>
      </span>
    </button>
  </div>
</div>
