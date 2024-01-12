@assets
  {{-- highlight code block style --}}
  @vite('node_modules/highlight.js/styles/atom-one-dark.css')

  {{-- highlight code block --}}
  @vite('resources/ts/highlight.ts')
@endassets

{{-- user information page --}}
<x-layouts.layout-main>
  <div class="container mx-auto flex-1">
    <div class="flex flex-col items-center justify-start px-4">
      {{-- user information, posts and comments --}}
      <div
        class="relative w-full max-w-sm md:max-w-[750px]"
        {{-- tab can only be 'information', 'posts', 'comments' --}}
        x-data="{
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
            }
        }"
        x-init="tabSelectedButtons = document.getElementById(tabSelected + '-tab-button');
        tabRepositionMarker(tabSelectedButtons);"
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
              <i class="{{ $tab['icon'] }}"></i>
              <span class="ml-2">{{ $tab['text'] }}</span>
            </button>
          @endforeach

          <div
            class="absolute left-0 z-10 h-full w-1/2 duration-300 ease-out"
            x-ref="tabMarker"
            x-cloak
          >
            <div class="h-full w-full rounded-md bg-gray-100 shadow-sm dark:bg-gray-800 dark:shadow-none"></div>
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
