<div class="w-full mr-0 space-y-6 md:w-[700px] xl:mr-6">

  {{-- 文章排序 --}}
  <div class="flex flex-col-reverse w-full md:flex-row md:justify-between text-sm">

    <nav
      class="flex w-full p-1 space-x-1 md:w-auto rounded-xl bg-gray-300 dark:bg-gray-600 dark:text-gray-50">

      @php
        $tabs = [
          ['value' => 'latest', 'text' => '最新文章', 'icon' => 'bi bi-stars'],
          ['value' => 'recent', 'text' => '最近更新', 'icon' => 'bi bi-wrench-adjustable'],
          ['value' => 'comment', 'text' => '最多留言', 'icon' => 'bi bi-chat-square-text-fill'],
        ]
      @endphp

      @foreach ($tabs as $tab)
        <a
          wire:click.prevent="orderChange('{{ $tab['value'] }}')"
          href="{{ $currentUrl . '?order=' . $tab['value'] }}"
          @class([
            'flex w-1/3 md:w-auto justify-center px-4 py-2 transition duration-300 rounded-lg',
            'bg-gray-50 dark:bg-gray-700' => $order === $tab['value'],
            'hover:bg-gray-50 dark:hover:bg-gray-700' => $order !== $tab['value'],
          ])
        >
          <i class="{{ $tab['icon'] }}"></i>
          <span class="ml-2">{{ $tab['text'] }}</span>
        </a>
      @endforeach
    </nav>

    {{-- 文章分類訊息-桌面裝置 --}}
    @if ($categoryId)
      <div
        class="flex items-center justify-end pb-2 pl-6 mb-6 border-b-2 border-gray-900 dark:text-gray-50 dark:border-gray-50 md:mb-0">
        <span class="font-bold">{{ $categoryName }}：</span>
        <span>{{ $categoryDescription }}</span>
      </div>
    @endif

    {{-- 文章標籤訊息-桌面裝置 --}}
    @if ($tagId)
      <div
        class="flex items-center justify-end pb-2 pl-6 mb-6 border-b-2 border-gray-900 dark:text-gray-50 dark:border-gray-50 md:mb-0">
        <span>標籤：</span>
        <span class="font-bold">{{ $tagName }}</span>
      </div>
    @endif
  </div>

  {{-- 文章列表 --}}
  @forelse($posts as $post)
    <x-card
      x-data="cardLink"
      x-on:click="directToCardLink($event, $refs)"
      class="flex flex-col justify-between cursor-pointer group md:flex-row"
    >
      {{-- 文章 --}}
      <div class="flex flex-col justify-between w-full">
        {{-- 文章標題 --}}
        <h1 class="mt-2 text-xl font-semibold md:mt-0 dark:text-gray-50">
          <a
            x-ref="cardLinkUrl"
            href="{{ $post->link_with_slug }}"
            class="group-gradient-underline-grow"
          >{{ $post->title }}</a>
        </h1>

        {{-- 文章大綱 --}}
        <div class="mt-2 text-gray-400 text-base">
          {{ $post->excerpt }}
        </div>

        {{-- 文章標籤 --}}
        @if ($post->tags_count > 0)
          <div class="flex flex-wrap items-center mt-2 text-base">
            <span class="mr-1 text-green-300 dark:text-indigo-300"><i class="bi bi-tags-fill"></i></span>

            @foreach ($post->tags as $tag)
              <x-tag :href="route('tags.show', ['tag' => $tag->id])">
                {{ $tag->name }}
              </x-tag>
            @endforeach
          </div>
        @endif

        {{-- 文章相關資訊 --}}
        <div class="flex items-center mt-2 space-x-2 text-base text-neutral-400">
          {{-- 文章分類資訊 --}}
          <div>
            <a
              href="{{ $post->category->link_with_name }}"
              title="{{ $post->category->name }}"
              class="hover:text-neutral-500 dark:hover:text-neutral-300"
            >
              <i class="{{ $post->category->icon }}"></i><span
                class="hidden ml-2 md:inline">{{ $post->category->name }}</span>
            </a>
          </div>
          <div>&bull;</div>
          {{-- 文章作者資訊 --}}
          <div>
            <a
              href="{{ route('users.index', ['user' => $post->user_id]) }}"
              title="{{ $post->user->name }}"
              class="hover:text-neutral-500 dark:hover:text-neutral-300"
            >
              <i class="bi bi-person-fill"></i><span class="hidden ml-2 md:inline">{{ $post->user->name }}</span>
            </a>
          </div>
          <div>&bull;</div>
          {{-- 文章發布時間 --}}
          <div>
            <a
              href="{{ $post->link_with_slug }}"
              title="文章發布於：{{ $post->created_at->toDateString() }}"
              class="hover:text-neutral-500 dark:hover:text-neutral-300"
            >
              <i class="bi bi-clock-fill"></i><span
                class="hidden ml-2 md:inline">{{ $post->created_at->diffForHumans() }}</span>
            </a>
          </div>
          <div>&bull;</div>
          <div>
            {{-- 文章留言數 --}}
            <a
              href="{{ $post->link_with_slug }}#comments"
              class="hover:text-neutral-500 dark:hover:text-neutral-300"
            >
              <i class="bi bi-chat-square-text-fill"></i><span
                class="hidden ml-2 md:inline">{{ $post->comment_count }}</span>
            </a>
          </div>
        </div>
      </div>
    </x-card>

  @empty
    <x-card
      class="flex items-center justify-center w-full transition duration-150 ease-in h-36 hover:-translate-x-2 hover:shadow-xl dark:text-gray-50">
      <span>Whoops！此分類底下還沒有文章，趕緊寫一篇吧！</span>
    </x-card>
  @endforelse

  <div>
    {{ $posts->onEachSide(1)->links() }}
  </div>
</div>
