@section('description', $post->excerpt)

@if (!empty($post->preview_url))
  @section('preview_url', $post->preview_url)
@endif

<x-layouts.layout-main>
  <div
    x-data
    x-init="// init show page
    hljs.highlightAll();
    codeBlockCopyButton($refs.postBody);
    processYoutubeOEmbeds();
    processTwitterOEmbeds();
    setTimeout(() => {
        twttr.widgets?.load($refs.postBody);
    }, 1000);
    setupProgressBar($refs.section, $refs.progressBar);
    setupScrollToTopButton($refs.scrollToTopBtn);
    setupSharer();

    // scroll to anchor
    if (window.location.hash !== '') {
        let target = document.querySelector(window.location.hash);

        if (target instanceof Element) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    }"
  >
    <style>
      /* media embed */
      iframe,
      .twitter-tweet {
        margin-left: auto;
        margin-right: auto;
      }

      /* hide google recaptcha badge */
      .grecaptcha-badge {
        visibility: hidden;
      }
    </style>

    <div class="relative animate-fade-in">
      <x-scroll-to-top-button x-ref="scrollToTopBtn" />

      <div class="container mx-auto">
        <div class="flex items-stretch justify-center lg:space-x-4">
          <div class="hidden lg:block lg:w-1/6"></div>

          <div class="flex w-full flex-col items-center justify-start p-2 md:w-[700px] lg:p-0">

            <x-card
              class="w-full"
              id="section"
              x-ref="section"
            >

              <div class="flex justify-between">
                {{-- post title --}}
                <h1 class="grow text-3xl font-bold dark:text-gray-50">{{ $post->title }}</h1>

                {{-- mobile dropdowns --}}
                @if (auth()->id() === $post->user_id)
                  <livewire:shared.posts.show-post-dropdowns :post-id="$post->id" />
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
                    href="{{ route('users.show', ['user' => $post->user_id]) }}"
                    title="{{ $post->user->name }}"
                    wire:navigate
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
                <div class="mt-4 flex flex-wrap items-center text-base">
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
                x-ref="postBody"
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
