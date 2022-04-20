<div
  x-data
  class="absolute top-0 hidden w-16 h-full xl:block left-[102%]"
>
  <div class="sticky flex flex-col items-center justify-center top-7">
    {{-- 編輯文章 --}}
    <a
      href="{{ route('posts.edit', ['post' => $post->id]) }}"
      class="inline-flex items-center justify-center w-16 h-16 transition duration-150 ease-in-out border border-transparent bg-emerald-500 group rounded-xl text-gray-50 hover:bg-emerald-600 active:bg-emerald-700 focus:outline-none focus:border-emerald-700 focus:ring ring-emerald-300"
    >
        <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:-rotate-12">
          <i class="bi bi-pencil-fill"></i>
        </span>
    </a>

    <form
      id="soft-delete-post"
      action="{{ route('posts.destroy', ['post' => $post->id]) }}"
      method="POST"
      class="hidden"
    >
      @csrf
      @method('DELETE')
    </form>

    {{-- 軟刪除 --}}
    <button
      x-on:click="
          if (confirm('您確定標記此文章為刪除狀態嗎？（30 天內還可以還原）')) {
            document.getElementById('soft-delete-post').submit()
          }
        "
      type="button"
      class="inline-flex items-center justify-center w-16 h-16 mt-4 transition duration-150 ease-in-out bg-orange-500 border border-transparent group rounded-xl text-gray-50 hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:border-orange-700 focus:ring ring-orange-300"
    >
        <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
          <i class="bi bi-file-earmark-x-fill"></i>
        </span>
    </button>

  </div>
</div>
