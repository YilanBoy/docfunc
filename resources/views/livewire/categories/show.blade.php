@section('title', $title)

{{-- 文章列表 --}}
<div class="container mx-auto max-w-7xl">
  <div
    class="flex flex-col items-center justify-start space-y-6 px-4 xl:flex-row xl:items-start xl:justify-center xl:space-y-0 xl:px-0"
  >
    {{-- 文章列表 --}}
    <livewire:posts.posts
      :currentUrl="url()->current()"
      :categoryId="$category->id"
      :categoryName="$category->name"
      :categoryDescription="$category->description"
    />

    {{-- 文章列表側邊欄 --}}
    <livewire:posts.partials.index-sidebar />

  </div>
</div>
