{{-- 會員文章 --}}
<x-card
  class="relative w-full text-lg"
  x-data="{
      activeAccordion: '',
      setActiveAccordion(id) {
          this.activeAccordion = (this.activeAccordion == id) ? '' : id
      }
  }"
  x-init="setActiveAccordion('accordion-1')"
>
  @forelse($postsGroupByYear as $year => $posts)
    <div
      class="group cursor-pointer rounded-md border bg-gray-50 duration-200 ease-out dark:bg-gray-800"
      x-data="{ id: $id('accordion') }"
      :id="id"
      :class="{
          'border-gray-300/60 dark:border-gray-600/60 text-gray-800 dark:text-gray-200': activeAccordion === id,
          'border-transparent text-gray-400 hover:text-gray-900 dark:hover:text-gray-200': activeAccordion !== id
      }"
      x-cloak
    >
      <button
        class="flex w-full select-none items-center justify-between px-5 py-4 text-left font-semibold"
        @click="setActiveAccordion(id)"
      >
        <span>{{ $year }}</span>

        {{-- across --}}
        <div
          class="relative flex h-2.5 w-2.5 items-center justify-center duration-300 ease-out"
          :class="{ 'rotate-90': activeAccordion === id }"
        >
          <div
            class="absolute h-full w-0.5 rounded-full bg-gray-400 group-hover:bg-gray-900 dark:group-hover:bg-gray-200"
          ></div>
          <div
            class="ease absolute h-0.5 w-full rounded-full bg-gray-400 duration-500 group-hover:bg-gray-900 dark:group-hover:bg-gray-200"
            :class="{ 'rotate-90': activeAccordion === id }"
          ></div>
        </div>
      </button>
      <div
        class="flex flex-col space-y-1"
        x-show="activeAccordion === id"
        x-collapse
        x-cloak
      >
        <livewire:user-info-page.posts-by-year
          :wire:key="$year"
          :user-id="$userId"
          :posts="$posts"
          :year="$year"
        />
      </div>
    </div>
  @empty
    <div class="flex h-32 items-center justify-center text-gray-400 dark:text-gray-600">
      <i class="bi bi-exclamation-circle-fill"></i><span class="ml-2">尚未發布任何文章</span>
    </div>
  @endforelse

</x-card>
