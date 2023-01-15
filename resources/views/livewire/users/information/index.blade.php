@section('title', $user->name . ' 的個人資訊')

@push('script')
  @vite('resources/ts/count-up.ts')
@endpush

{{-- 個人資訊 --}}
<div class="container flex-1 mx-auto max-w-7xl">
  <div class="flex flex-col items-center justify-start px-4">

    {{-- 會員資訊、文章與留言 --}}
    <div
      x-data="{
          url: new URL(window.location.href),
          tab: new URLSearchParams(location.search).get('tab') || 'information'
        }"
      class="w-full space-y-6 lg:w-7/12"
    >
      {{-- 切換顯示選單 --}}
      <nav
        class="flex w-full p-1 space-x-1 md:w-4/5 lg:w-1/2 rounded-xl bg-gray-300 dark:bg-gray-600 dark:text-gray-50 text-sm">

        @php
          $tabs = [
            ['value' => 'information', 'text' => '個人資訊', 'icon' => 'bi bi-info-circle-fill'],
            ['value' => 'posts', 'text' => '發布文章', 'icon' => 'bi bi-file-earmark-post-fill'],
            ['value' => 'comments', 'text' => '留言紀錄', 'icon' => 'bi bi-chat-square-text-fill'],
          ]
        @endphp

        @foreach($tabs as $tab)
          <a
            x-on:click.prevent="
              tab = '{{ $tab['value'] }}'
              url.searchParams.set('tab', '{{ $tab['value'] }}')
              history.pushState(null, document.title, url.toString())
            "
            href="#"
            :class="{
                'bg-gray-50 dark:bg-gray-700': tab === '{{ $tab['value'] }}',
                'hover:bg-gray-50 dark:hover:bg-gray-700': tab !== '{{ $tab['value'] }}'
              }"
            class="flex justify-center w-1/3 px-2 py-2 transition duration-300 rounded-lg"
          >
            <i class="{{ $tab['icon'] }}"></i>
            <span class="ml-2">{{ $tab['text'] }}</span>
          </a>
        @endforeach
      </nav>

      {{-- 會員資訊 --}}
      <div
        x-cloak
        x-show="tab === 'information'"
        x-transition:enter.duration.300ms
      >
        <livewire:users.information.personal-information :user-id="$user->id"/>
      </div>

      {{-- 會員文章 --}}
      <div
        x-cloak
        x-show="tab === 'posts'"
        x-transition:enter.duration.300ms
      >
        <livewire:users.information.posts.posts :user-id="$user->id"/>
      </div>

      {{-- 會員留言 --}}
      <div
        x-cloak
        x-show="tab === 'comments'"
        x-transition:enter.duration.300ms
      >
        <livewire:users.information.comments :user-id="$user->id"/>
      </div>
    </div>
  </div>
</div>
