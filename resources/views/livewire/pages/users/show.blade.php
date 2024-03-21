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
@endassets

@script
  <script>
    // tab can only be 'information', 'posts', 'comments'
    Alpine.data('userShowTabs', () => ({
      tabSelected: $persist('information'),
      tabButtonClicked(tabButton) {
        this.tabSelected = tabButton.id.replace('-tab-button', '');
        this.tabRepositionMarker(tabButton);
      },
      tabRepositionMarker(tabButton) {
        this.$refs.tabMarker.style.width = tabButton.offsetWidth + 'px';
        this.$refs.tabMarker.style.height = tabButton.offsetHeight + 'px';
        this.$refs.tabMarker.style.left = tabButton.offsetLeft + 'px';
      },
      tabContentActive(tabContent) {
        return this.tabSelected === tabContent.id.replace('-content', '');
      },
      init() {
        let tabSelectedButtons = document.getElementById(this.tabSelected + '-tab-button');
        this.tabRepositionMarker(tabSelectedButtons);
      }
    }));
  </script>
@endscript

{{-- user information page --}}
<x-layouts.layout-main>
  <div class="container mx-auto flex-1">
    <div class="flex flex-col items-center justify-start px-4">
      {{-- user information, posts and comments --}}
      <div
        class="w-full max-w-3xl"
        x-data="userShowTabs"
      >
        <div
          class="relative mb-6 inline-grid h-10 w-full select-none grid-cols-3 items-center justify-center rounded-lg border border-gray-100 bg-white p-1 text-gray-500 dark:border-gray-800 dark:bg-gray-700 dark:text-gray-50"
        >
          @foreach ($tabs as $tab)
            <button
              class="relative z-20 inline-flex h-8 w-full cursor-pointer items-center justify-center whitespace-nowrap rounded-md px-3 text-sm font-medium transition-all"
              id="{{ $tab['value'] }}-tab-button"
              type="button"
              x-on:click="tabButtonClicked($el)"
            >
              @switch($tab['value'])
                @case('posts')
                  <x-icon.file-earmark-richtext class="w-4" />
                @break

                @case('comments')
                  <x-icon.chat-square-text class="w-4" />
                @break

                @default
                  <x-icon.info-circle class="w-4" />
              @endswitch
              <span class="ml-2">{{ $tab['text'] }}</span>
            </button>
          @endforeach

          <div
            class="absolute left-0 z-10 h-full w-1/2 duration-300 ease-out"
            x-ref="tabMarker"
            x-cloak
          >
            <div class="h-full w-full rounded-md bg-gray-100 dark:bg-gray-800"></div>
          </div>
        </div>

        {{-- user information content --}}
        <div
          id="information-content"
          x-cloak
          x-show="tabContentActive($el)"
          x-transition:enter.duration.300ms
        >
          <livewire:shared.users.info-cards :user-id="$user->id" />
        </div>

        {{-- user posts content --}}
        <div
          id="posts-content"
          x-cloak
          x-show="tabContentActive($el)"
          x-transition:enter.duration.300ms
        >
          <livewire:shared.users.posts :user-id="$user->id" />
        </div>

        {{-- user comments content --}}
        <div
          id="comments-content"
          x-cloak
          x-show="tabContentActive($el)"
          x-transition:enter.duration.300ms
        >
          <livewire:shared.users.comments :user-id="$user->id" />
        </div>
      </div>
    </div>
  </div>
</x-layouts.layout-main>
