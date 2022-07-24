@section('title', $post->title)

@section('description', $post->excerpt)

@if(!is_null($post->preview_url))
  @section('preview_url', $post->preview_url)
@endif

@section('css')
  {{-- 程式碼區塊高亮 --}}
  @vite('node_modules/highlight.js/scss/atom-one-dark.scss')

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
  @vite('resources/ts/scroll-to-top-btn.ts')
  {{-- media embed --}}
  @vite('resources/ts/oembed/twitter-widgets.ts')
  @vite('resources/ts/oembed/oembed-media-embed.ts')
  {{-- 程式碼區塊高亮 --}}
  @vite('resources/ts/highlight.ts')
  {{-- 程式碼複製按鈕 --}}
  @vite('resources/ts/copy-code-btn.ts')
  {{-- 文章閱讀進度條 --}}
  @vite('resources/ts/progress-bar.ts')
  {{-- 社群分享 --}}
  @vite('resources/ts/sharer.ts')
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
      <div class="grid grid-cols-5 gap-4">
        <div class="hidden xl:block xl:col-span-1"></div>
        <div class="flex flex-col items-center justify-start col-span-10 px-4 xl:col-span-3 xl:px-0">

          <x-card id="section" class="w-full">
            {{-- 軟刪除文章 form --}}
            <form
              id="soft-delete-post"
              action="{{ route('posts.destroy', ['post' => $post->id]) }}"
              method="POST"
              class="hidden"
            >
              @csrf
              @method('DELETE')
            </form>

            <div class="flex justify-between">
              {{-- 文章標題 --}}
              <h1 class="text-3xl font-bold grow dark:text-gray-50">{{ $post->title }}</h1>

              {{-- 文章選單-行動裝置 --}}
              @includeWhen(auth()->id() === $post->user_id, 'posts.partials.mobile-show-menu')

            </div>

            {{-- 文章資訊 --}}
            <div class="flex items-center mt-4 space-x-2 text-neutral-400">
              {{-- 分類 --}}
              <div>
                <a
                  class="hover:text-neutral-500 dark:hover:text-neutral-300"
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
                  class="hover:text-neutral-500 dark:hover:text-neutral-300"
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
                  class="hover:text-neutral-500 dark:hover:text-neutral-300"
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
                  class="hover:text-neutral-500 dark:hover:text-neutral-300"
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
                <span class="mr-1 text-neutral-400"><i class="bi bi-tags-fill"></i></span>

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
          <x-card class="grid w-full grid-cols-12 gap-4 mt-6">
            <div class="flex items-center justify-start col-span-12 md:col-span-2 md:justify-center">
              <img
                class="w-16 h-16 rounded-full"
                src="{{ $post->user->gravatar_url }}"
                alt="{{ $post->user->name }}"
              >
            </div>
            <div class="col-span-12 space-y-2 md:col-span-10">
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
        <div class="hidden xl:block xl:col-span-1">
          {{-- 文章選單-桌面裝置 --}}
          @include('posts.partials.desktop-show-menu')
        </div>
      </div>
    </div>
  </div>

  <div id="progress-bar"
       class="fixed top-0 left-0 w-0 h-[5px] bg-gradient-to-r from-green-500 via-teal-500 to-sky-500 dark:from-pink-500 dark:via-purple-500 dark:to-indigo-500 z-[100] transition-all duration-300 ease-out"></div>
</x-app-layout>
