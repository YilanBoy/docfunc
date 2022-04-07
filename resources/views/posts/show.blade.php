@section('title', $post->title)

@section('description', $post->excerpt)

@section('css')
  {{-- 程式碼區塊高亮 --}}
  <link href="{{ asset('css/atom-one-dark.css') }}" rel="stylesheet">
  {{-- 文章閱讀進度條 --}}
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
  <script src="{{ asset('js/highlight.js') }}"></script>
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

        <x-card
          id="section"
          class="relative w-full xl:w-7/12"
        >

          {{-- 文章選單-桌面裝置 --}}
          @includeWhen(auth()->id() === $post->user_id, 'posts.partials.desktop-show-menu')

          <div class="flex justify-between">
            {{-- 文章標題 --}}
            <h1 class="text-3xl font-bold grow dark:text-gray-50">{{ $post->title }}</h1>

            {{-- 文章選單-行動裝置 --}}
            @includeWhen(auth()->id() === $post->user_id, 'posts.partials.mobile-show-menu')

          </div>

          {{-- 文章資訊 --}}
          <div class="flex items-center mt-4 space-x-2 text-slate-400">
            {{-- 分類 --}}
            <div>
              <a
                class="hover:text-slate-500 dark:hover:text-slate-300"
                href="{{ $post->category->link_with_name }}"
                title="{{ $post->category->name }}"
              >
                <i class="{{ $post->category->icon }}"></i>
                <span class="ml-2">{{ $post->category->name }}</span>
              </a>
            </div>

            <div>&bull;</div>

            {{-- 作者 --}}
            <div>
              <a
                class="hover:text-slate-500 dark:hover:text-slate-300"
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
                class="hover:text-slate-500 dark:hover:text-slate-300"
                href="{{ $post->link_with_slug }}"
                title="文章發布於：{{ $post->created_at }}"
              >
                <i class="bi bi-clock-fill"></i>
                <span class="ml-2">{{ $post->created_at->diffForHumans() }}</span>
              </a>
            </div>

            <div class="hidden md:block">&bull;</div>

            {{-- 留言數 --}}
            <div class="hidden md:block">
              <a
                class="hover:text-slate-500 dark:hover:text-slate-300"
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
              <span class="mr-1 text-slate-400"><i class="bi bi-tags-fill"></i></span>

              @foreach ($post->tags as $tag)
                <x-tag :href="route('tags.show', ['tag' => $tag->id])">
                  {{ $tag->name }}
                </x-tag>
              @endforeach
            </div>
          @endif

          {{-- 文章內容 --}}
          <div
            id="blog-post"
            class="mt-4 blog-post max-w-none"
          >
            {!! $post->body !!}
          </div>
        </x-card>

        {{-- 作者簡介 --}}
        <x-card class="flex items-center justify-start w-full mt-6 xl:w-7/12">
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
            <p class="whitespace-pre-wrap dark:text-gray-50">{{ $post->user->introduction }}</p>
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
