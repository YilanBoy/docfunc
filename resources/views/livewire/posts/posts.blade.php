<div class="mr-0 w-full space-y-6 md:w-[700px] xl:mr-6">

  {{-- 文章排序 --}}
  <div class="flex w-full flex-col-reverse text-sm md:flex-row md:justify-between">

    <nav class="flex w-full space-x-1 rounded-xl p-1 dark:text-gray-50 md:w-auto">

      @php
        $tabs = [['value' => 'latest', 'text' => '最新文章', 'icon' => 'bi bi-stars'], ['value' => 'recent', 'text' => '最近更新', 'icon' => 'bi bi-wrench-adjustable'], ['value' => 'comment', 'text' => '最多留言', 'icon' => 'bi bi-chat-square-text-fill']];
      @endphp

      @foreach ($tabs as $tab)
        <a
          href="{{ $currentUrl . '?order=' . $tab['value'] }}"
          wire:click.prevent="orderChange('{{ $tab['value'] }}')"
          @class([
              'flex w-1/3 md:w-auto justify-center px-4 py-2 transition duration-300 rounded-lg ',
              'bg-gray-50 dark:bg-gray-800' => $order === $tab['value'],
              'hover:bg-gray-50 dark:hover:bg-gray-800' => $order !== $tab['value'],
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
        class="mb-6 flex items-center justify-end border-b-2 border-gray-900 pb-2 pl-6 dark:border-gray-50 dark:text-gray-50 md:mb-0"
      >
        <span class="font-bold">{{ $categoryName }}：</span>
        <span>{{ $categoryDescription }}</span>
      </div>
    @endif

    {{-- 文章標籤訊息-桌面裝置 --}}
    @if ($tagId)
      <div
        class="mb-6 flex items-center justify-end border-b-2 border-gray-900 pb-2 pl-6 dark:border-gray-50 dark:text-gray-50 md:mb-0"
      >
        <span>標籤：</span>
        <span class="font-bold">{{ $tagName }}</span>
      </div>
    @endif
  </div>

  {{-- 文章列表 --}}
  @forelse($posts as $post)
    <x-card
      class="group flex cursor-pointer flex-col justify-between md:flex-row"
      x-data="cardLink"
      x-on:click="directToCardLink($event, $refs)"
    >
      {{-- 文章 --}}
      <div class="flex w-full flex-col justify-between">
        {{-- 文章標題 --}}
        <h1 class="mt-2 text-xl font-semibold dark:text-gray-50 md:mt-0">
          <a
            class="group-gradient-underline-grow"
            href="{{ $post->link_with_slug }}"
            x-ref="cardLinkUrl"
          >{{ $post->title }}</a>
        </h1>

        {{-- 文章大綱 --}}
        <div class="mt-2 text-base text-gray-500">
          {{ $post->excerpt }}
        </div>

        {{-- 文章標籤 --}}
        @if ($post->tags_count > 0)
          <div class="mt-2 flex flex-wrap items-center text-base">
            <span class="mr-1 text-green-300 dark:text-lividus-600"><i class="bi bi-tags-fill"></i></span>

            @foreach ($post->tags as $tag)
              <x-tag :href="route('tags.show', ['tag' => $tag->id])">
                {{ $tag->name }}
              </x-tag>
            @endforeach
          </div>
        @endif

        {{-- 文章相關資訊 --}}
        <div class="mt-2 hidden space-x-2 text-base text-neutral-500 md:flex md:items-center">
          {{-- 文章分類資訊 --}}
          <div>
            <a
              class="hover:text-neutral-500 dark:hover:text-neutral-300"
              href="{{ $post->category->link_with_name }}"
              title="{{ $post->category->name }}"
            >
              <i class="{{ $post->category->icon }}"></i><span class="ml-2">{{ $post->category->name }}</span>
            </a>
          </div>
          <div>&bull;</div>
          {{-- 文章作者資訊 --}}
          <div>
            <a
              class="hover:text-neutral-500 dark:hover:text-neutral-300"
              href="{{ route('users.index', ['user' => $post->user_id]) }}"
              title="{{ $post->user->name }}"
            >
              <i class="bi bi-person-fill"></i><span class="ml-2">{{ $post->user->name }}</span>
            </a>
          </div>
          <div>&bull;</div>
          {{-- 文章發布時間 --}}
          <div>
            <a
              class="hover:text-neutral-500 dark:hover:text-neutral-300"
              href="{{ $post->link_with_slug }}"
              title="文章發布於：{{ $post->created_at->toDateString() }}"
            >
              <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $post->created_at->diffForHumans() }}</span>
            </a>
          </div>
          <div>&bull;</div>
          <div>
            {{-- 文章留言數 --}}
            <a
              class="hover:text-neutral-500 dark:hover:text-neutral-300"
              href="{{ $post->link_with_slug }}#comments"
            >
              <i class="bi bi-chat-square-text-fill"></i><span class="ml-2">{{ $post->comment_counts }}</span>
            </a>
          </div>
        </div>
      </div>
    </x-card>

  @empty
    <x-card
      class="flex h-36 w-full items-center justify-center transition duration-150 ease-in hover:-translate-x-2 hover:shadow-xl dark:text-gray-50"
    >
      <span>Whoops！此分類底下還沒有文章，趕緊寫一篇吧！</span>
    </x-card>
  @endforelse

  <div>
    {{ $posts->onEachSide(1)->links() }}
  </div>
</div>
