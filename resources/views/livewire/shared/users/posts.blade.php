@php
  $latestYear = array_key_first($this->postsGroupByYear);
@endphp

@if (!empty($this->postsGroupByYear))
  {{-- 會員文章 --}}
  <x-card
    class="relative w-full text-lg"
    x-data="{
        currentYear: {{ Js::from($latestYear) }},
    }"
  >

    <div
      class="relative mb-6 flex justify-end"
      x-data="{ dropdownOpen: false }"
    >
      <button
        class="inline-flex w-full items-center justify-center rounded-md border bg-gray-50 px-4 py-2 text-lg font-medium transition-colors hover:bg-neutral-100 focus:bg-white focus:outline-none focus:ring-2 focus:ring-neutral-200/60 focus:ring-offset-2 active:bg-white disabled:pointer-events-none disabled:opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-50 dark:hover:bg-gray-700 dark:focus:bg-gray-600 dark:focus:ring-gray-600 dark:focus:ring-offset-gray-800 dark:active:bg-gray-600"
        type="button"
        x-on:click="dropdownOpen=true"
        x-text="currentYear + ' 年的文章'"
      >
      </button>

      <div
        class="absolute right-0 top-1 z-50 mt-12 w-40"
        x-show="dropdownOpen"
        x-on:click.away="dropdownOpen=false"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="-translate-y-2"
        x-transition:enter-end="translate-y-0"
        x-cloak
      >
        <div
          class="mt-1 rounded-md border border-neutral-200/70 bg-white p-1 text-lg text-neutral-700 shadow-md dark:border-gray-600 dark:bg-gray-800 dark:text-gray-50"
        >
          @foreach (array_keys($this->postsGroupByYear) as $year)
            <button
              class="group relative flex w-full cursor-default select-none items-center justify-between rounded px-2 py-1.5 outline-none hover:bg-neutral-100 hover:text-neutral-900 data-[disabled]:pointer-events-none data-[disabled]:opacity-50 dark:hover:bg-gray-700 dark:hover:text-gray-50"
              type="button"
              x-on:click="
              currentYear = @js($year);
              dropdownOpen = false;
            "
            >
              <span>{{ $year }}</span>
            </button>
          @endforeach
        </div>
      </div>
    </div>

    @foreach ($this->postsGroupByYear as $year => $posts)
      <div
        class="rounded-md border bg-gray-50 duration-200 dark:border-gray-700 dark:bg-gray-800"
        x-show="currentYear === @js($year)"
        x-cloak
        x-transition
      >
        <livewire:shared.users.posts-group-by-year
          :wire:key="$year"
          :user-id="$userId"
          :posts="$posts"
          :year="$year"
        />
      </div>
    @endforeach

  </x-card>
@else
  <x-card class="flex h-32 items-center justify-center text-gray-400 dark:text-gray-600">
    <x-icon.exclamation-circle class="w-6" />
    <span class="ml-2">尚未發布任何文章</span>
  </x-card>
@endif
