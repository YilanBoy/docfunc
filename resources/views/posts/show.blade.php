@section('title', $post->title)

@section('description', $post->excerpt)

@section('css')
  <link href="{{ asset('css/content-styles.css') }}" rel="stylesheet">
  <link href="{{ asset('prism/prism.css') }}" rel="stylesheet">
  <link href="{{ asset('css/progress-bar.css') }}" rel="stylesheet">

  <style>
    /* media embed */
    iframe,
    .twitter-tweet {
      margin-left: auto;
      margin-right: auto;
    }
  </style>
@endsection

@section('scripts')
  {{-- 至頂按鈕 --}}
  <script src="{{ asset('js/scroll-to-top-btn.js') }}"></script>
  {{-- media embed --}}
  <script src="{{ asset('js/twitter-widgets.js') }}"></script>
  <script src="{{ asset('js/oembed-media-embed.js') }}"></script>
  {{-- 程式碼區塊高亮 --}}
  <script src="{{ asset('prism/prism.js') }}"></script>
  {{-- 程式碼複製按鈕 --}}
  <script src="{{ asset('js/copy-code-btn.js') }}"></script>

  <script src="{{ asset('js/progress-bar.js') }}"></script>
@endsection

{{-- 文章內容 --}}
<x-app-layout>
  <div class="relative animate-fade-in">
    {{-- 置頂按鈕 --}}
    <button
      id="scroll-to-top-btn"
      title="Go to top"
      class="fixed z-10 hidden w-16 h-16 transition duration-150 ease-in bg-blue-600 rounded-full shadow-md bottom-7 right-7 text-gray-50 hover:scale-110 hover:shadow-xl"
    >
      <span class="m-auto text-3xl font-bold">
        <i class="bi bi-arrow-up"></i>
      </span>
    </button>

    <div class="container mx-auto max-w-7xl">
      <div class="flex flex-col items-center justify-start px-4 xl:px-0">

        <x-card id="section" class="relative w-full xl:w-2/3">

          {{-- 文章選單-桌面裝置 --}}
          <div
            x-data
            class="absolute top-0 hidden w-16 h-full xl:block left-[102%]"
          >
            <div class="sticky flex flex-col items-center justify-center top-7">
              @if (auth()->id() === $post->user_id)
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
                  action="{{ route('posts.softDelete', ['post' => $post->id]) }}"
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
              @endif
            </div>
          </div>

          <div class="flex justify-between">
            {{-- 文章標題 --}}
            <h1 class="text-3xl font-bold grow dark:text-gray-50">{{ $post->title }}</h1>

            {{-- 文章選單-行動裝置 --}}
            @if (auth()->id() === $post->user_id)
              <div
                x-data="{ editMenuIsOpen: false }"
                class="relative xl:hidden"
              >
                <div>
                  <button
                    x-on:click="editMenuIsOpen = ! editMenuIsOpen"
                    x-on:click.outside="editMenuIsOpen = false"
                    x-on:keydown.escape.window="editMenuIsOpen = false"
                    type="button"
                    class="text-2xl text-gray-400 hover:text-gray-700 focus:text-gray-700 dark:hover:text-gray-50 dark:focus:text-gray-50"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <i class="bi bi-three-dots-vertical"></i>
                  </button>
                </div>

                <div
                  x-cloak
                  x-show="editMenuIsOpen"
                  x-transition.origin.top.right
                  class="absolute right-0 z-10 w-48 p-2 mt-2 text-gray-700 rounded-md shadow-lg bg-gray-50 ring-1 ring-black ring-opacity-20 dark:bg-gray-700 dark:text-gray-50 dark:ring-gray-500"
                  role="menu"
                  aria-orientation="vertical"
                  tabindex="-1"
                >
                  {{-- 編輯文章 --}}
                  <a
                    href="{{ route('posts.edit', ['post' => $post->id]) }}"
                    role="menuitem"
                    tabindex="-1"
                    class="block px-4 py-2 rounded-md hover:bg-gray-200 active:bg-gray-100 dark:hover:bg-gray-600"
                  >
                    <i class="bi bi-pencil-fill"></i><span class="ml-2">編輯</span>
                  </a>

                  {{-- 軟刪除 --}}
                  <button
                    x-on:click="
                      if (confirm('您確定標記此文章為刪除狀態嗎？（30 天內還可以還原）')) {
                        document.getElementById('soft-delete-post').submit()
                      }
                    "
                    type="button"
                    role="menuitem"
                    tabindex="-1"
                    class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-200 active:bg-gray-100 dark:hover:bg-gray-600"
                  >
                    <i class="bi bi-file-earmark-x-fill"></i><span class="ml-2">刪除標記</span>
                  </button>
                </div>
              </div>
            @endif
          </div>

          {{-- 文章資訊 --}}
          <div class="flex items-center mt-4 space-x-2 text-gray-500 dark:text-gray-300">
            {{-- 分類 --}}
            <div>
              <a
                class="hover:text-gray-700 dark:hover:text-gray-50"
                href="{{ $post->category->link_with_name }}"
                title="{{ $post->category->name }}"
              >
                <i class="{{ $post->category->icon }}"></i><span
                  class="ml-2">{{ $post->category->name }}</span>
              </a>
            </div>

            <div>&bull;</div>

            {{-- 作者 --}}
            <div>
              <a
                class="hover:text-gray-700 dark:hover:text-gray-50"
                href="{{ route('users.index', ['user' => $post->user_id]) }}"
                title="{{ $post->user->name }}"
              >
                <i class="bi bi-person-fill"></i><span class="ml-2">{{ $post->user->name }}</span>
              </a>
            </div>

            <div class="hidden md:block">&bull;</div>

            {{-- 發布時間 --}}
            <div class="hidden md:block">
              <a
                class="hover:text-gray-700 dark:hover:text-gray-50"
                href="{{ $post->link_with_slug }}"
                title="文章發布於：{{ $post->created_at }}"
              >
                <i class="bi bi-clock-fill"></i><span
                  class="ml-2">{{ $post->created_at->diffForHumans() }}</span>
              </a>
            </div>

            <div class="hidden md:block">&bull;</div>

            {{-- 留言數 --}}
            <div class="hidden md:block">
              <a
                class="hover:text-gray-700 dark:hover:text-gray-50"
                href="{{ $post->link_with_slug }}#comments"
              >
                <i class="bi bi-chat-square-text-fill"></i><span
                  class="ml-2">{{ $post->comment_count }}</span>
              </a>
            </div>
          </div>

          {{-- 文章標籤 --}}
          @if ($post->tags()->exists())
            <div class="flex items-center mt-4">
              <span class="mr-1 text-zinc-500 dark:text-zinc-300"><i class="bi bi-tags-fill"></i></span>

              @foreach ($post->tags as $tag)
                <x-tag :href="route('tags.show', ['tag' => $tag->id])">
                  {{ $tag->name }}
                </x-tag>
              @endforeach
            </div>
          @endif

          {{-- 文章內容 --}}
          <div id="blog-post" class="mt-4 ck-content blog-post max-w-none">
            {!! $post->body !!}
          </div>
        </x-card>

        {{-- 作者簡介 --}}
        <x-card class="flex items-center justify-start w-full mt-6 xl:w-2/3">
          <div class="flex-none p-2 mr-4 none md:flex md:justify-center md:items-center">
            <img
              class="w-16 h-16 rounded-full"
              src="{{ $post->user->gravatar_url }}"
              alt="{{ $post->user->name }}"
            >
          </div>
          <div class="space-y-2">
            <div class="text-gray-400 uppercase">written by</div>
            <a
              href="{{ route('users.index', ['user' => $post->user->id]) }}"
              class="inline-block text-2xl font-bold gradient-underline-grow dark:text-gray-50"
            >
              {{ $post->user->name }}
            </a>
            <div class="dark:text-gray-50">{!! nl2br(e($post->user->introduction)) !!}</div>
          </div>
        </x-card>

        {{-- 留言回覆 --}}
        @auth
          <livewire:comments.comment-box
            :postId="$post->id"
            :commentCount="$post->comment_count"
          />
        @endauth

        {{-- 留言列表 --}}
        <livewire:comments.comments :postId="$post->id"/>
      </div>
    </div>
  </div>

  <div id="progress-bar"></div>
</x-app-layout>
