@section('title', '編輯文章')

@section('css')
  <link href="{{ asset('css/editor.css') }}" rel="stylesheet">
  <link href="{{ asset('css/tagify.css') }}" rel="stylesheet">
@endsection

@section('scripts')
  {{-- 載入 Ckeditor --}}
  <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
  <script src="{{ asset('js/editor.js') }}"></script>
  {{-- 載入 Tagify --}}
  <script src="{{ asset('js/tagify.js') }}"></script>
@endsection

{{-- 編輯文章 --}}
<x-app-layout>
  <div class="container mx-auto max-w-7xl">
    <div class="flex items-start justify-center min-h-screen px-4 mt-6 xl:px-0">

      <div class="flex flex-col items-center justify-center w-full space-y-6 xl:w-2/3">
        {{-- 頁面標題 --}}
        <div class="text-2xl text-gray-700 fill-current dark:text-gray-50">
          <i class="bi bi-pencil-fill"></i><span class="ml-4">編輯文章</span>
        </div>

        {{-- 文章編輯資訊-桌面裝置 --}}
        <x-card class="relative w-full">
          <div class="hidden xl:block absolute top-0 left-[102%] w-52 h-full">
            <div class="sticky flex flex-col top-9">
              {{-- 字數提示 --}}
              <div class="flex items-center justify-start w-full p-4 bg-gradient-to-r from-white to-white/0 rounded-xl dark:text-gray-50 dark:from-gray-700 dark:to-gray-700/0">
                <span class="update-post-characters"></span>
              </div>

              {{-- 儲存按鈕 --}}
              <button
                type="submit"
                form="edit-post"
                class="inline-flex items-center justify-center w-16 h-16 mt-4 transition duration-150 ease-in-out bg-blue-500 border border-transparent save-post group rounded-xl text-gray-50 hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300"
              >
                <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
                  <i class="bi bi-save2-fill"></i>
                </span>
              </button>
            </div>
          </div>

          {{-- 驗證錯誤訊息 --}}
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form id="edit-post" action="{{ route('posts.update', ['post' => $post->id]) }}" method="POST">
            @method('PUT')
            @csrf

            {{-- 文章標題 --}}
            <div>
              <label for="title" class="hidden">文章標題</label>

              <input
                type="text"
                id="title"
                name="title"
                placeholder="文章標題"
                value="{{ old('title', $post->title) }}"
                required
                autofocus
                class="w-full mt-2 border border-gray-300 rounded-md shadow-sm form-input focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50 dark:placeholder-white"
              >
            </div>

            {{-- 文章分類 --}}
            <div class="mt-5">
              <label for="category_id" class="hidden">分類</label>

              <select
                id="category_id"
                name="category_id"
                required
                class="w-full h-10 border border-gray-300 rounded-md shadow-sm form-select focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50"
              >
                <option value="" hidden disabled {{ $post->id ? '' : 'selected' }}>請選擇分類</option>
                {{-- 這裡的 $categories 使用的是 view composer 來取得值，詳細可查看 ViewServiceProvider --}}
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- 文章標籤 --}}
            <div class="mt-5">
              <label for="tag-input" class="hidden">標籤（最多 5 個）</label>

              <input
                id="tag-input"
                type="text"
                name="tags"
                value="{{ old('tags', $post->tags_json) }}"
                placeholder="標籤（最多 5 個）"
                class="h-10 text-sm bg-white rounded-md dark:bg-gray-600"
              >
            </div>

            {{-- 文章內容 --}}
            <div class="mt-5 blog-post max-w-none">
              <label for="editor" class="hidden">內文</label>

              <textarea id="editor" name="body" placeholder="分享一些很棒的事情吧!">{{ old('body', $post->body) }}</textarea>
            </div>

            {{-- 文章編輯資訊-行動裝置 --}}
            <div class="flex items-center justify-between mt-4 xl:hidden">
              {{-- 顯示文章總字數 --}}
              <div>
                <span class="update-characters"></span>
              </div>

              {{-- 儲存按鈕 --}}
              <x-button class="save-post">
                <i class="bi bi-save2-fill"></i><span class="ml-2">儲存</span>
              </x-button>
            </div>
          </form>
        </x-card>

      </div>
    </div>
  </div>
</x-app-layout>
