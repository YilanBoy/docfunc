@script
  <script>
    Alpine.data('commentMenu', () => ({
      openCreateCommentModal() {
        this.$dispatch('open-create-comment-modal', {
          parentId: null,
          replyTo: null
        })
      }
    }));
  </script>
@endscript

<div
  class="mt-6 w-full"
  x-data="commentMenu"
>
  <div class="flex justify-between">
    {{-- show comments count --}}
    <div class="flex items-center dark:text-gray-50">
      <x-icon.chat-square-text class="w-5" />
      <span class="ml-2">{{ $commentCounts }} 則留言</span>
    </div>

    <button
      class="group relative overflow-hidden rounded-xl bg-emerald-500 px-6 py-2 [transform:translateZ(0)] before:absolute before:bottom-0 before:left-0 before:h-full before:w-full before:origin-[100%_100%] before:scale-x-0 before:bg-lividus-600 before:transition before:duration-500 before:ease-in-out hover:before:origin-[0_0] hover:before:scale-x-100 dark:bg-lividus-600 dark:before:bg-emerald-500"
      type="button"
      {{-- the comment group name should be full name --}}
      x-on:click="openCreateCommentModal"
    >
      <div class="relative z-0 flex items-center text-lg text-gray-200 transition duration-500 ease-in-out">
        <x-icon.chat-dots class="w-5" />

        @if (auth()->check())
          <span class="ml-2">新增留言</span>
        @else
          <span class="ml-2">訪客留言</span>
        @endif
      </div>
    </button>
  </div>
</div>
