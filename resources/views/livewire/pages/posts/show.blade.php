@section('description', $post->excerpt)

@if (!empty($post->preview_url))
  @section('preview_url', $post->preview_url)
@endif

@assets
  {{-- highlight code block style --}}
  @vite('node_modules/highlight.js/styles/atom-one-dark.css')

  {{-- highlight code block --}}
  @vite('resources/ts/highlight.ts')
  {{-- code block copy button --}}
  @vite('resources/ts/copy-code-btn.ts')
  {{-- post read pregress bar --}}
  @vite('resources/ts/progress-bar.ts')
  {{-- to the top button --}}
  @vite('resources/ts/scroll-to-top-btn.ts')
  {{-- social media share button --}}
  @vite('resources/ts/sharer.ts')
  {{-- media embed --}}
  @vite('resources/ts/oembed/embed-youtube-oembed.ts')
  @vite('resources/ts/oembed/embed-twitter-oembed.ts')
  @vite('resources/ts/oembed/twitter-widgets.ts')
@endassets

@script
  <script>
    Alpine.data('showPostPage', () => ({
      init() {
        hljs.highlightAll();
        codeBlockCopyButton(this.$refs.postBody);
        processYoutubeOEmbeds();
        processTwitterOEmbeds();
        setTimeout(() => {
          twttr.widgets?.load(this.$refs.postBody);
        }, 1000);
        setupProgressBar(this.$refs.section, this.$refs.progressBar);
        setupScrollToTopButton(this.$refs.scrollToTopBtn);
        setupSharer();

        if (window.location.hash !== '') {
          let target = document.querySelector(window.location.hash);

          if (target instanceof Element) {
            target.scrollIntoView({
              behavior: 'smooth'
            });
          }
        }
      }
    }));
  </script>
@endscript

<x-layouts.layout-main>
  <div x-data="showPostPage">
    <div class="relative animate-fade-in">
      <x-scroll-to-top-button x-ref="scrollToTopBtn" />

      <div class="container mx-auto">
        <div class="flex items-stretch justify-center lg:space-x-4">
          <div class="hidden lg:block lg:w-1/6"></div>

          <div class="flex w-full flex-col items-center justify-start p-2 md:w-[800px] lg:p-0">

            <x-card
              class="w-full"
              id="section"
              x-ref="section"
            >

              <div class="flex justify-between">
                {{-- post title --}}
                <h1 class="grow text-3xl dark:text-gray-50">{{ $post->title }}</h1>

                {{-- mobile dropdowns --}}
                @if (auth()->id() === $post->user_id)
                  <livewire:shared.posts.show-post-dropdowns :post-id="$post->id" />
                @endif

              </div>

              {{-- post information --}}
              <div class="mt-4 flex items-center space-x-2 text-base text-neutral-400">
                {{-- classfication --}}
                <div class="flex items-center">
                  <div class="size-4">{!! $post->category->icon !!}</div>

                  <span class="ml-2">{{ $post->category->name }}</span>
                </div>

                <div>&bull;</div>

                {{-- author --}}
                <a
                  class="flex items-center hover:text-neutral-500 dark:hover:text-neutral-300"
                  href="{{ route('users.show', ['user' => $post->user_id]) }}"
                  title="{{ $post->user->name }}"
                  wire:navigate
                >
                  <x-icon.person class="w-4" />
                  <span class="ml-2">{{ $post->user->name }}</span>
                </a>

                <div class="hidden md:block">&bull;</div>

                {{-- post created time --}}
                <div class="hidden items-center md:flex">
                  <x-icon.clock class="w-4" />
                  <span class="ml-2">{{ $post->created_at->toDateString() }}</span>

                  @if ($post->created_at->toDateString() !== $post->updated_at->toDateString())
                    <span>{{ '(最後更新於 ' . $post->updated_at->toDateString() . ')' }}</span>
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
                  <x-icon.tags class="mr-1 w-4 text-green-300 dark:text-lividus-600" />

                  @foreach ($post->tags as $tag)
                    <x-tag :href="route('tags.show', ['tag' => $tag->id])">
                      {{ $tag->name }}
                    </x-tag>
                  @endforeach
                </div>
              @endif

              {{-- post thumbnail --}}
              @if (!empty($post->preview_url))
                <div
                  class="-mx-5 mt-4"
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
                  href="{{ route('users.show', ['user' => $post->user->id]) }}"
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

          <div class="hidden lg:block lg:w-1/6">
            {{-- desktop sidemenu --}}
            <livewire:shared.posts.show-post-sidemenu
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
