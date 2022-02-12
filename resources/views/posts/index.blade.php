@section('title', $pageTitle)

{{-- 文章列表 --}}
<x-app-layout>
  <div class="container mx-auto max-w-7xl">
    <div class="flex flex-col justify-center px-4 space-y-6 xl:space-y-0 xl:flex-row xl:px-0">
      {{-- 文章列表 --}}
      <livewire:posts
        :currentUrl="url()->current()"
        :categoryId="$category->id ?? 0"
        :categoryName="$category->name ?? ''"
        :categoryDescription="$category->description ?? ''"
        :tagId="$tag->id ?? 0"
        :tagName="$tag->name ?? ''"
      />

      {{-- 文章列表側邊欄 --}}
      <div class="w-full space-y-6 xl:w-80">
        {{-- 介紹 --}}
        <div class="p-0.5 bg-gradient-to-br from-pink-500 via-purple-500 to-indigo-500 rounded-xl shadow-md">
          <div class="p-5 bg-gray-50 rounded-xl dark:bg-gray-700 dark:text-gray-50">
            <h3 class="pb-3 mb-3 text-lg font-semibold text-center border-b-2 border-black dark:border-white">
              歡迎來到 <span class="font-mono">{{ config('app.name') }}</span>！
            </h3>
            <span>
              紀錄生活上的大小事
              <br>
              此部落格使用 Laravel、Alpine.js 與 Tailwind CSS 開發
            </span>
            <div class="flex items-center justify-center mt-7">
              <a
                href="{{ route('posts.create') }}"
                class="block group [transform:translateZ(0)] px-6 py-3 rounded-xl overflow-hidden bg-emerald-500 relative before:absolute before:bg-blue-600 before:top-1/2 before:left-1/2 before:h-8 before:w-8 before:-translate-y-1/2 before:-translate-x-1/2 before:rounded-full before:scale-[0] before:opacity-0 hover:before:scale-[6] hover:before:opacity-100 before:transition before:ease-in-out before:duration-700"
              >
                <span class="relative z-0 text-xl font-semibold text-gray-200 transition duration-700 ease-in-out">
                  <i class="bi bi-pencil-fill"></i><span class="ml-2">新增文章</span>
                </span>
              </a>
            </div>
          </div>
        </div>

        {{-- 熱門標籤 --}}
        @if ($popularTags->count())
          <x-card class="dark:text-gray-50">
            <h3 class="pb-3 mb-3 text-lg font-semibold text-center border-b-2 border-black dark:border-white">
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
            <h3 class="pb-3 mb-3 text-lg font-semibold text-center border-b-2 border-black dark:border-white">
              <i class="bi bi-file-earmark-code-fill"></i><span class="ml-2">學習資源推薦</span>
            </h3>
            <div class="flex flex-col">
              @foreach ($links as $link)
                <a
                  href="{{ $link->link }}"
                  target="_blank"
                  rel="nofollow noopener noreferrer"
                  class="flex p-2 rounded-md hover:bg-gray-200 dark:text-gray-50 dark:hover:bg-gray-600"
                >
                  <i class="bi bi-link-45deg"></i><span class="ml-2">{{ $link->title }}</span>
                </a>
              @endforeach
            </div>
          </x-card>
        @endif
      </div>

    </div>
  </div>
</x-app-layout>
