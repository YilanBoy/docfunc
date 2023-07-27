@section('title', $post->title)

@section('description', $post->excerpt)

@if (!is_null($post->preview_url))
  @section('preview_url', $post->preview_url)
@endif

@push('css')
  {{-- highlight code block --}}
  @vite('node_modules/highlight.js/scss/base16/material-palenight.scss')

  <style>
    /* media embed */
    iframe,
    .twitter-tweet {
      margin-left: auto;
      margin-right: auto;
    }
  </style>

  {{-- hide google recaptcha badge --}}
  <style>
    .grecaptcha-badge {
      visibility: hidden;
    }
  </style>
@endpush

@push('scriptInHead')
  {{-- highlight code block --}}
  @vite('resources/ts/highlight.ts')
@endpush

@push('scriptInBody')
  {{-- to the top button --}}
  @vite('resources/ts/scroll-to-top-btn.ts')
  {{-- media embed --}}
  @vite('resources/ts/oembed/twitter-widgets.ts')
  @vite('resources/ts/oembed/oembed-media-embed.ts')
  {{-- code block copy button --}}
  @vite('resources/ts/copy-code-btn.ts')
  {{-- post read pregress bar --}}
  @vite('resources/ts/progress-bar.ts')
  {{-- social media share button --}}
  @vite('resources/ts/sharer.ts')
@endpush

<div
  x-data
  x-init="hljs.highlightAll();"
>
  <div class="relative animate-fade-in">
    {{-- to the top button --}}
    <button
      class="fixed bottom-7 right-7 z-10 hidden h-16 w-16 rounded-full bg-green-600 text-gray-50 shadow-md transition duration-150 ease-in hover:scale-110 hover:shadow-xl dark:bg-lividus-600"
      id="scroll-to-top-btn"
      type="button"
      title="Go to top"
    >
      <span class="m-auto text-3xl font-bold">
        <i class="bi bi-arrow-up"></i>
      </span>
    </button>

    <div class="container mx-auto">
      <div class="flex items-stretch justify-center lg:space-x-4">
        <div class="hidden lg:block lg:w-1/6"></div>

        <div class="flex w-full flex-col items-center justify-start p-2 md:w-[700px] lg:p-0">

          <x-card
            class="w-full"
            id="section"
          >

            <div class="flex justify-between">
              {{-- post title --}}
              <h1 class="grow text-3xl font-bold dark:text-gray-50">{{ $post->title }}</h1>

              {{-- mobile post sidebar --}}
              @if (auth()->id() === $post->user_id)
                <livewire:posts.partials.mobile-show-menu :post-id="$post->id" />
              @endif

            </div>

            {{-- post information --}}
            <div class="mt-4 flex items-center space-x-2 text-base text-neutral-400">
              {{-- classfication --}}
              <div>
                <i class="{{ $post->category->icon }}"></i><span class="ml-2">{{ $post->category->name }}</span>
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
                <i class="bi bi-clock-fill"></i>
                <span class="ml-2">{{ $post->created_at->toDateString() }}</span>

                @if ($post->created_at->toDateString() !== $post->updated_at->toDateString())
                  <span>{{ '(最後更新於 ' . $post->updated_at->toDateString() . ')' }}</span>
                @endif
              </div>

              <div class="hidden md:block">&bull;</div>

              {{-- comments count --}}
              <div class="hidden md:block">
                <a
                  class="hover:text-neutral-500 dark:hover:text-neutral-300"
                  href="{{ $post->link_with_slug }}#comments"
                >
                  <i class="bi bi-chat-square-text-fill"></i><span class="ml-2">{{ $post->comment_counts }}</span>
                </a>
              </div>

            </div>

            {{-- post tags --}}
            @if ($post->tags()->exists())
              <div class="mt-4 flex items-center text-base">
                <span class="mr-1 text-green-300 dark:text-lividus-600"><i class="bi bi-tags-fill"></i></span>

                @foreach ($post->tags as $tag)
                  <x-tag :href="route('tags.show', ['tag' => $tag->id])">
                    {{ $tag->name }}
                  </x-tag>
                @endforeach
              </div>
            @endif

            {{-- post body --}}
            <div
              class="post-body mt-4"
              id="post-body"
            >
              {!! $post->body !!}
            </div>
          </x-card>

          {{-- about author --}}
          <x-card class="mt-6 grid w-full grid-cols-12 gap-4">
            <div class="col-span-12 flex items-center justify-start md:col-span-2 md:justify-center">
              <img
                class="h-16 w-16 rounded-full"
                src="{{ $post->user->gravatar_url }}"
                alt="{{ $post->user->name }}"
              >
            </div>
            <div class="col-span-12 space-y-2 md:col-span-10">
              <div class="uppercase text-gray-400">written by</div>
              <a
                class="gradient-underline-grow inline-block text-2xl font-bold dark:text-gray-50"
                href="{{ route('users.index', ['user' => $post->user->id]) }}"
              >
                {{ $post->user->name }}
              </a>
              <p class="whitespace-pre-wrap dark:text-gray-50">{{ $post->user->introduction }}</p>
            </div>
          </x-card>

          {{-- comment box --}}
          <livewire:comments.reply
            :postId="$post->id"
            :commentCounts="$post->comment_counts"
          />

          {{-- comments list --}}
          <livewire:comments.comments
            :postId="$post->id"
            :comment-counts="$post->comment_counts"
          />
        </div>

        <div class="hidden lg:block lg:w-1/6">
          {{-- 文章選單-桌面裝置 --}}
          <livewire:posts.partials.desktop-show-menu
            :post-id="$post->id"
            :post-title="$post->title"
            :author-id="$post->user_id"
          />
        </div>
      </div>
    </div>
  </div>

  <livewire:comments.create-comment-modal :post-id="$post->id" />

  <livewire:comments.edit-comment-modal />

  <div
    class="fixed left-0 top-0 z-20 h-[5px] w-0 bg-gradient-to-r from-green-500 via-teal-500 to-sky-500 transition-all duration-300 ease-out dark:from-pink-500 dark:via-purple-500 dark:to-indigo-500"
    id="progress-bar"
  >
  </div>
</div>
