@section('title', $post->title)

@section('description', $post->excerpt)

@if(!is_null($post->preview_url))
  @section('preview_url', $post->preview_url)
@endif

@section('css')
  {{-- highlight code block --}}
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
  {{-- to the top button --}}
  @vite('resources/ts/scroll-to-top-btn.ts')
  {{-- media embed --}}
  @vite('resources/ts/oembed/twitter-widgets.ts')
  @vite('resources/ts/oembed/oembed-media-embed.ts')
  {{-- highlight code block --}}
  @vite('resources/ts/highlight.ts')
  {{-- code block copy button --}}
  @vite('resources/ts/copy-code-btn.ts')
  {{-- post read pregress bar --}}
  @vite('resources/ts/progress-bar.ts')
  {{-- social media share button --}}
  @vite('resources/ts/sharer.ts')
@endsection

<x-app-layout>
  <div class="relative animate-fade-in">
    {{-- to the top button --}}
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
      <div class="flex space-x-4 justify-center items-stretch">
        <div class="hidden lg:block lg:w-1/6"></div>

        <div class="flex flex-col items-center justify-start w-full lg:w-2/3 xl:w-7/12 px-4 lg:px-0">

          <x-card id="section" class="w-full">
            {{-- soft delete form --}}
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
              {{-- post title --}}
              <h1 class="text-3xl font-bold grow dark:text-gray-50">{{ $post->title }}</h1>

              {{-- mobile post sidebar --}}
              @includeWhen(auth()->id() === $post->user_id, 'posts.partials.mobile-show-menu')

            </div>

            {{-- post information --}}
            <div class="flex items-center mt-4 space-x-2 text-neutral-400 text-base">
              {{-- classfication --}}
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

              {{-- author --}}
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

              {{-- post created time --}}
              <div class="hidden md:block">
                <a
                  class="hover:text-neutral-500 dark:hover:text-neutral-300"
                  href="{{ $post->link_with_slug }}"
                  title="發表時間：{{ $post->created_at->toDateString() }}"
                >
                  <i class="bi bi-clock-fill"></i>
                  <span class="ml-2">{{ $post->created_at->toDateString() }}</span>

                  @if ($post->created_at->toDateString() !== $post->updated_at->toDateString())
                    <span>{{ '(最後更新於 ' . $post->updated_at->toDateString() . ')' }}</span>
                  @endif

                </a>
              </div>

              <div class="hidden md:block">&bull;</div>

              {{-- comments count --}}
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

            {{-- post tags --}}
            @if ($post->tags()->exists())
              <div class="flex items-center mt-4 text-base">
                <span class="mr-1 text-neutral-400"><i class="bi bi-tags-fill"></i></span>

                @foreach ($post->tags as $tag)
                  <x-tag :href="route('tags.show', ['tag' => $tag->id])">
                    {{ $tag->name }}
                  </x-tag>
                @endforeach
              </div>
            @endif

            {{-- post body --}}
            <div
              id="blog-post"
              class="mt-4 blog-post max-w-none"
            >
              {!! $post->body !!}
            </div>
          </x-card>

          {{-- about author --}}
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

          {{-- comment box --}}
          @auth
            <livewire:comments.comment-box
              :postId="$post->id"
              :commentCount="$post->comment_count"
            />
          @endauth

          {{-- comments list --}}
          <livewire:comments.comments :postId="$post->id"/>
        </div>

        <div class="hidden lg:block lg:w-1/6">
          {{-- 文章選單-桌面裝置 --}}
          @include('posts.partials.desktop-show-menu')
        </div>
      </div>
    </div>
  </div>

  <div id="progress-bar"
       class="fixed top-0 left-0 w-0 h-[5px] bg-gradient-to-r from-green-500 via-teal-500 to-sky-500 dark:from-pink-500 dark:via-purple-500 dark:to-indigo-500 z-20 transition-all duration-300 ease-out"></div>
</x-app-layout>
