@section('title', $title)

{{-- 文章列表 --}}
<div class="container mx-auto max-w-7xl">
  <div
    class="flex flex-col justify-start items-center px-4 space-y-6 xl:space-y-0 xl:flex-row xl:justify-center xl:items-start xl:px-0">
    {{-- 文章列表 --}}
    <livewire:posts.posts
      :currentUrl="url()->current()"
      :categoryId="$category->id"
      :categoryName="$category->name"
      :categoryDescription="$category->description"
    />

    {{-- 文章列表側邊欄 --}}
    <livewire:posts.partials.index-sidebar/>

  </div>
</div>