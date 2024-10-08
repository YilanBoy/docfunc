@section('description', $post->excerpt)

@if (!empty($post->preview_url))
  @section('preview_url', $post->preview_url)
@endif

@assets
  {{-- highlight code block style --}}
  @vite('node_modules/highlight.js/styles/atom-one-dark.css')
  <style>
    .dark .hljs {
      background: #171717;
    }
  </style>

  {{-- highlight code block --}}
  @vite('resources/ts/highlight.ts')
  {{-- code block copy button --}}
  @vite('resources/ts/copy-code-btn.ts')
  {{-- post read pregress bar --}}
  @vite('resources/ts/progress-bar.ts')
  {{-- scroll --}}
  @vite('resources/ts/scroll-to-top-btn.ts')
  @vite('resources/ts/scroll-to-anchor.ts')
  {{-- social media share button --}}
  @vite('resources/ts/sharer.ts')
  {{-- media embed --}}
  @vite('resources/ts/oembed/embed-youtube-oembed.ts')
  @vite('resources/ts/oembed/embed-twitter-oembed.ts')
  {{-- post content --}}
  @vite('resources/ts/post-outline.ts')
@endassets

@script
  <script>
    Alpine.data('showPostPage', () => ({
      init() {
        setupPostOutline(this.$refs.postOutline, this.$refs.postBody);
        hljs.highlightAll();
        codeBlockCopyButton(this.$refs.postBody);
        processYoutubeOembeds();
        processTwitterOembeds(this.$refs.postBody);
        setupProgressBar(this.$refs.postCard, this.$refs.progressBar);
        setupScrollToTopButton(this.$refs.scrollToTopBtn);
        setupSharer();
        scrollToAnchor();
      }
    }));
  </script>
@endscript

<x-layouts.layout-main>
  <div x-data="showPostPage">
    <div class="relative">
      <x-scroll-to-top-button x-ref="scrollToTopBtn" />

      <div class="container mx-auto">
        <div class="flex animate-fade-in items-stretch justify-center lg:space-x-4">
          <div class="hidden xl:block xl:w-1/5">
            {{-- content menu --}}
            <div
              class="sticky top-1/2 flex -translate-y-1/2 flex-col"
              x-ref="postOutline"
            ></div>
          </div>

          <div class="flex w-full max-w-3xl flex-col items-center justify-start px-2 xl:px-0">
            <x-card
              class="w-full"
              x-ref="postCard"
            >
              <article>
                {{-- post title --}}
                <h1 class="text-4xl leading-relaxed text-green-600 dark:text-lividus-500">{{ $post->title }}</h1>

                {{-- post information --}}
                <div class="mt-4 flex items-center space-x-2 text-base text-neutral-400">
                  {{-- classfication --}}
                  <div class="flex items-center">
                    <div class="size-4">{!! $post->category->icon !!}</div>

                    <span class="ml-2">{{ $post->category->name }}</span>
                  </div>

                  <div class="hidden md:block">&bull;</div>

                  {{-- post created time --}}
                  <div class="hidden items-center md:flex">
                    <x-icon.clock class="w-4" />
                    <time
                      class="ml-2"
                      datetime="{{ $post->created_at->toDateString() }}"
                    >{{ $post->created_at->toDateString() }}</time>

                    @if ($post->created_at->toDateString() !== $post->updated_at->toDateString())
                      <time datetime="{{ $post->updated_at->toDateString() }}">
                        {{ '(最後更新於 ' . $post->updated_at->toDateString() . ')' }}
                      </time>
                    @endif
                  </div>

                  <div class="hidden md:block">&bull;</div>

                  {{-- comments count --}}
                  <a
                    class="hidden hover:text-neutral-500 dark:hover:text-neutral-300 md:flex md:items-center"
                    href="{{ $post->link_with_slug }}#comments"
                  >
                    <x-icon.chat-square-text class="w-4" />
                    <span class="ml-2">{{ $post->comment_counts }}</span>
                  </a>

                </div>

                {{-- post tags --}}
                @if ($post->tags()->exists())
                  <div class="mt-4 flex flex-wrap items-center text-base">
                    <x-icon.tags class="mr-1 w-4 text-green-300 dark:text-lividus-700" />

                    @foreach ($post->tags as $tag)
                      <x-tag :href="route('tags.show', ['tagId' => $tag->id])">
                        {{ $tag->name }}
                      </x-tag>
                    @endforeach
                  </div>
                @endif

                {{-- post thumbnail --}}
                @if (!empty($post->preview_url))
                  <div
                    class="-mx-4 mt-4"
                    id="post-thumbnail"
                  >
                    <img
                      class="w-full"
                      src="{{ $post->preview_url }}"
                      alt="{{ $post->title }}"
                    >
                  </div>
                @endif

                {{-- post body --}}
                <div
                  class="post-body mt-4"
                  id="post-body"
                  x-ref="postBody"
                >
                  {!! $post->body !!}
                </div>
              </article>

              {{-- mobile menu --}}
              @if (auth()->id() === $post->user_id)
                <livewire:shared.posts.mobile-menu :post-id="$post->id" />
              @endif
            </x-card>

            {{-- about author --}}
            <x-card class="mt-6 grid w-full grid-cols-12 gap-4">
              <div class="col-span-12 flex items-center justify-start md:col-span-2 md:justify-center">
                <img
                  class="h-20 w-20 rounded-full"
                  src="{{ $post->user->gravatar_url }}"
                  alt="{{ $post->user->name }}"
                >
              </div>
              <div class="col-span-12 space-y-2 md:col-span-10">
                <div class="uppercase text-gray-400">written by</div>
                <a
                  class="gradient-underline-grow inline-block text-2xl dark:text-gray-50"
                  href="{{ route('users.show', ['userId' => $post->user->id]) }}"
                  wire:navigate
                >
                  {{ $post->user->name }}
                </a>
                <p class="whitespace-pre-wrap dark:text-gray-50">{{ $post->user->introduction }}</p>
              </div>
            </x-card>

            {{-- comment box --}}
            <livewire:shared.comments.reply
              :post-id="$post->id"
              :comment-counts="$post->comment_counts"
            />

            {{-- comments list --}}
            <livewire:shared.comments.comments
              :post-id="$post->id"
              :post-author-id="$post->user_id"
              :comment-counts="$post->comment_counts"
            />
          </div>

          <div class="hidden xl:block xl:w-1/5">
            {{-- desktop side menu --}}
            <livewire:shared.posts.side-menu
              :post-id="$post->id"
              :post-title="$post->title"
              :author-id="$post->user_id"
            />
          </div>
        </div>
      </div>
    </div>

    <livewire:shared.comments.create-comment-modal :post-id="$post->id" />

    <livewire:shared.comments.edit-comment-modal />

    <x-porgress-bar />
  </div>
</x-layouts.layout-main>
