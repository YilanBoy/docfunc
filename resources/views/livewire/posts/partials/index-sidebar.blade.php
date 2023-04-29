<div class="hidden space-y-6 xl:block xl:w-[320px]">
  {{-- 介紹 --}}
  <div
    class="rounded-xl bg-gradient-to-br from-green-500 via-teal-500 to-sky-500 p-0.5 shadow-lg dark:from-pink-500 dark:via-purple-500 dark:to-indigo-500"
  >
    <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-700 dark:text-gray-50">
      <h3 class="mb-3 border-b-2 border-black pb-3 text-center text-lg font-semibold dark:border-white">
        歡迎來到 <span class="font-mono">{{ config('app.name') }}</span>！
      </h3>
      <span>
        紀錄學習與生活上的大小事！
        <br>
        此部落格使用 Laravel、Alpine.js 與 Tailwind CSS 開發～
      </span>
      <div class="mt-7 flex items-center justify-center">
        <a
          href="{{ route('posts.create') }}"
          class="group relative flex w-full items-center justify-center overflow-hidden rounded-lg bg-emerald-500 px-4 py-2 [transform:translateZ(0)] before:absolute before:left-1/2 before:top-1/2 before:h-8 before:w-8 before:-translate-x-1/2 before:-translate-y-1/2 before:scale-[0] before:rounded-full before:bg-blue-600 before:opacity-0 before:transition before:duration-700 before:ease-in-out hover:before:scale-[10] hover:before:opacity-100"
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
            href="{{ $link->link }}"
            target="_blank"
            rel="nofollow noopener noreferrer"
            class="flex rounded-md p-2 hover:bg-gray-200 dark:text-gray-50 dark:hover:bg-gray-600"
          >
            <i class="bi bi-link-45deg"></i><span class="ml-2">{{ $link->title }}</span>
          </a>
        @endforeach
      </div>
    </x-card>
  @endif

  <a
    x-data="{ webFeedUrl: $el.getAttribute('href') }"
    x-on:click.prevent="
      navigator.clipboard.writeText(webFeedUrl).then(
        () => $el.innerHTML = `<i class='bi bi-check-lg'></i><span class='ml-2'>複製成功</span>`,
        () => $el.innerHTML = `<i class='bi bi-x-lg'></i><span class='ml-2'>複製失敗</span>`
      );

      setTimeout(() => $el.innerHTML = `<i class='bi bi-rss-fill'></i><span class='ml-2'>訂閱文章</span>`, 2000);
    "
    href="{{ route('feeds.main') }}"
    target="_blank"
    rel="nofollow noopener"
    class="inline-flex w-full items-center justify-center rounded-lg border border-transparent bg-zinc-500 px-4 py-2 text-lg font-semibold tracking-widest text-gray-50 ring-zinc-300 transition duration-150 ease-in-out hover:bg-zinc-600 focus:border-zinc-700 focus:outline-none focus:ring active:bg-zinc-700 dark:ring-zinc-800"
  >
    <i class="bi bi-rss-fill"></i><span class="ml-2">訂閱文章</span>
  </a>
</div>
