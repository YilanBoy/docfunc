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
      <livewire:posts.partials.index-sidebar />

    </div>
  </div>
</x-app-layout>
