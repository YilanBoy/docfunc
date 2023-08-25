<div class="hidden space-y-6 xl:block xl:w-[320px]">
  {{-- 介紹 --}}
  <div
    class="rounded-xl bg-gradient-to-br from-green-500 via-teal-500 to-sky-500 p-0.5 shadow-lg dark:from-pink-500 dark:via-purple-500 dark:to-indigo-500 dark:shadow-none"
  >
    <div class="group rounded-xl bg-gray-50 p-5 dark:bg-gray-800 dark:text-gray-50">
      <h3
        class="w-full bg-gradient-to-r from-emerald-500 to-sky-500 bg-clip-text text-center font-jetbrains-mono text-2xl font-semibold text-transparent dark:border-white dark:from-pink-500 dark:to-violet-500"
      >
        echo 'Hello World';
      </h3>

      <hr class="my-4 h-0.5 border-0 bg-gray-300 dark:bg-gray-700">

      <span class="group-gradient-underline-grow">
        嘗試用部落格來紀錄自己學習的過程，與生活上的大小事。此部落格使用 TALL Stack 所開發🚀
      </span>

      <div class="mt-8 flex items-center justify-center">
        <a
          class="group relative flex w-full items-center justify-center overflow-hidden rounded-lg bg-emerald-600 px-4 py-2 [transform:translateZ(0)] before:absolute before:left-1/2 before:top-1/2 before:h-8 before:w-8 before:-translate-x-1/2 before:-translate-y-1/2 before:scale-[0] before:rounded-full before:bg-blue-600 before:opacity-0 before:transition before:duration-700 before:ease-in-out hover:before:scale-[10] hover:before:opacity-100"
          href="{{ route('posts.create') }}"
          wire:navigate
        >
          <span class="relative z-0 text-lg font-semibold text-gray-200 transition duration-700 ease-in-out">
            <i class="bi bi-pencil-fill"></i><span class="ml-2">新增文章</span>
          </span>
        </a>
      </div>
    </div>
  </div>

  {{-- 熱門標籤 --}}
  @if ($popularTags->count())
    <x-card class="dark:text-gray-50">
      <h3 class="mb-3 border-b-2 border-black pb-3 text-center text-lg font-semibold dark:border-white">
        <i class="bi bi-tags-fill"></i><span class="ml-2">熱門標籤</span>
      </h3>
      <div class="flex flex-wrap">
        @foreach ($popularTags as $popularTag)
          <x-tag :href="route('tags.show', ['tag' => $popularTag->id])">
            {{ $popularTag->name }}
          </x-tag>
        @endforeach
      </div>
    </x-card>
  @endif

  {{-- 學習資源推薦 --}}
  @if ($links->count())
    <x-card class="dark:text-gray-50">
      <h3 class="mb-3 border-b-2 border-black pb-3 text-center text-lg font-semibold dark:border-white">
        <i class="bi bi-file-earmark-code-fill"></i><span class="ml-2">學習資源推薦</span>
      </h3>
      <div class="flex flex-col">
        @foreach ($links as $link)
          <a
            class="flex rounded-md p-2 hover:bg-gray-200 dark:text-gray-50 dark:hover:bg-gray-700"
            href="{{ $link->link }}"
            target="_blank"
            rel="nofollow noopener noreferrer"
          >
            <i class="bi bi-link-45deg"></i><span class="ml-2">{{ $link->title }}</span>
          </a>
        @endforeach
      </div>
    </x-card>
  @endif

  <a
    class="inline-flex w-full items-center justify-center rounded-lg border border-transparent bg-zinc-500 px-4 py-2 text-lg font-semibold tracking-widest text-gray-50 ring-zinc-300 transition duration-150 ease-in-out hover:bg-zinc-600 focus:border-zinc-700 focus:outline-none focus:ring active:bg-zinc-700 dark:bg-zinc-600 dark:ring-zinc-800 dark:hover:bg-zinc-500"
    href="{{ route('feeds.main') }}"
    x-data="{ webFeedUrl: $el.getAttribute('href') }"
    x-on:click.prevent="
      navigator.clipboard.writeText(webFeedUrl).then(
        () => $el.innerHTML = `<i class='bi bi-check-lg'></i><span class='ml-2'>複製成功</span>`,
        () => $el.innerHTML = `<i class='bi bi-x-lg'></i><span class='ml-2'>複製失敗</span>`
      );

      setTimeout(() => $el.innerHTML = `<i class='bi bi-rss-fill'></i><span class='ml-2'>訂閱文章</span>`, 2000);
    "
    target="_blank"
    rel="nofollow noopener"
  >
    <i class="bi bi-rss-fill"></i><span class="ml-2">訂閱文章</span>
  </a>
</div>
