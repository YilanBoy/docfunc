@section('title', '新增文章')

@section('css')
  @vite([
    'resources/css/editor.css',
    'node_modules/@yaireo/tagify/dist/tagify.css',
    'resources/css/missing-content-style.css',
  ])
@endsection

@section('scripts')
  {{-- Ckeditor --}}
  <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
  @vite('resources/js/editor.js')
  {{-- Tagify --}}
  @vite('resources/ts/tagify.ts')
@endsection

{{-- 新增文章 --}}
<x-app-layout>
  <div class="container mx-auto max-w-7xl">
    <div class="flex items-start justify-center px-4 xl:px-0">

      <div class="flex flex-col items-center justify-center w-full space-y-6 xl:w-3/5">
        {{-- 頁面標題 --}}
        <div class="text-2xl text-gray-700 fill-current dark:text-gray-50">
          <i class="bi bi-pencil-fill"></i><span class="ml-4">新增文章</span>
        </div>

        {{-- 文章編輯資訊-桌面裝置 --}}
        <x-card class="relative w-full">

          <div class="hidden xl:block absolute top-0 left-[102%] w-52 h-full">
            <div class="sticky flex flex-col top-9">
              {{-- 字數提示 --}}
              <div
                class="flex items-center justify-start w-full p-4 bg-gradient-to-r from-white to-white/0 rounded-xl dark:text-gray-50 dark:from-gray-700 dark:to-gray-700/0"
              >
                <span class="update-post-characters"></span>
              </div>

              {{-- 儲存按鈕 --}}
              <button
                type="submit"
                form="create-post"
                class="inline-flex items-center justify-center mt-4 transition duration-150 ease-in-out bg-blue-500 border border-transparent w-14 h-14 save-post group rounded-xl text-gray-50 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300"
              >
                <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
                  <i class="bi bi-save2-fill"></i>
                </span>
              </button>
            </div>
          </div>

          {{-- 驗證錯誤訊息 --}}
          <x-auth-validation-errors class="mb-4" :errors="$errors"/>

          <form id="create-post" action="{{ route('posts.store') }}" method="POST">
            @csrf

            {{-- 文章標題 --}}
            <div class="mt-2">
              <label for="title" class="hidden">文章標題</label>

              <input
                type="text"
                id="title"
                name="title"
                placeholder="文章標題"
                value="{{ old('title') }}"
                required
                autofocus
                class="w-full border border-gray-300 rounded-md shadow-sm form-input focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50 dark:placeholder-white"
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
                <option value="" hidden disabled @selected(!old('category_id'))>
                  請選擇分類
                </option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- 文章標籤 --}}
            <div class="mt-5">
              <label for="tag-input" class="hidden">標籤 (最多 5 個)</label>

              <input
                id="tag-input"
                type="text"
                name="tags"
                value="{{ old('tags') }}"
                placeholder="標籤 (最多 5 個)"
                class="h-10 text-sm bg-white rounded-md dark:bg-gray-600"
              >
            </div>

            {{-- 文章預覽圖 --}}
            <div
              x-data="{
                showPreviewUrlInput: false,
                previewUrl: ''
              }"
              class="mt-5"
            >
              <div class="flex items-center">
                <input
                  x-on:click="showPreviewUrlInput = !showPreviewUrlInput"
                  x-bind:checked="showPreviewUrlInput"
                  id="show_preview_url_input"
                  type="checkbox"
                  value=""
                  class="form-checkbox w-5 h-5 rounded"
                >
                <label
                  for="show_preview_url_input"
                  class="ml-2 font-medium dark:text-gray-50"
                >預覽圖設定</label>
              </div>

              <label for="preview_url" class="hidden">預覽圖連結</label>

              <input
                x-cloak
                x-show="showPreviewUrlInput"
                x-transition.origin.top.left
                x-bind:required="showPreviewUrlInput"
                x-bind:disabled="!showPreviewUrlInput"
                x-model="previewUrl"
                type="text"
                id="preview_url"
                name="preview_url"
                placeholder="預覽圖連結"
                value="{{ old('preview_url') }}"
                class="w-full border border-gray-300 rounded-md shadow-sm form-input focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50 dark:placeholder-white mt-5"
              >

              <template x-if="previewUrl !== '' && showPreviewUrlInput">
                <img
                  x-bind:src="previewUrl"
                  class="w-64 rounded-md dark:border dark:border-gray-300 dark:text-gray-50 mt-5"
                  alt="圖片連結有誤"
                >
              </template>

            </div>

            {{-- 文章內容 --}}
            <div class="mt-5 max-w-none">
              <label for="editor" class="hidden">內文</label>

              <textarea id="editor" name="body" placeholder="分享一些很棒的事情吧!">{{ old('body') }}</textarea>
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
