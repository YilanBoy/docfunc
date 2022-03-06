{{-- 會員基本資訊 --}}
<div class="grid w-full grid-cols-6 gap-6 dark:text-gray-50">
  <x-card class="flex flex-col items-center justify-between col-span-6 md:col-span-2">
    {{-- 大頭貼 --}}
    <img
      class="rounded-full h-36 w-36"
      src="{{ $user->gravatar_url }}"
      alt="{{ $user->name }}"
    >

    {{-- 會員名稱 --}}
    <span class="flex items-center justify-center mt-2 text-3xl font-semibold">
      {{ $user->name }}
    </span>

    <span class="mt-2 text-xs">
      註冊於 {{ $user->created_at->format('Y / m / d') . '（' . $user->created_at->diffForHumans() . '）' }}
    </span>
  </x-card>

  <x-card class="col-span-6 md:col-span-4 dark:text-gray-50">
    <h3 class="w-full pb-3 mb-3 text-2xl font-semibold border-b-2 border-black dark:border-white">
      <span class="ml-2">個人簡介</span>
    </h3>

    @if ($user->introduction)
      <p class="flex items-center justify-start w-full whitespace-pre-wrap">{{ $user->introduction }}</p>
    @else
      <p class="flex items-center justify-center w-full whitespace-pre-wrap">目前尚無個人簡介～</p>
    @endif
  </x-card>

  <x-card class="col-span-6 dark:text-gray-50">
    <h3 class="w-full pb-3 mb-3 text-2xl font-semibold border-b-2 border-black dark:border-white">
      <span class="ml-2">各類文章統計</span>
    </h3>

    <div class="grid grid-cols-12 gap-1">
      @foreach ($categories as $category)
        <div class="col-span-12">
          {{ $category->name }}
        </div>
        <div class="flex items-center col-span-11">

          @php
            $barWidth = $category->posts->count()
                ? (int) (($category->posts->count() / $user->posts->count()) * 100)
                : 0.2
          @endphp

          <div style="width: {{ $barWidth }}%">
            <div
              class="h-4 transition-all duration-300 rounded-sm animate-grow-width bg-gradient-to-r from-emerald-400 to-blue-400"></div>
          </div>

        </div>
        <div class="flex items-center justify-end col-span-1 text-lg font-semibold text-sky-500">
          {{ $category->posts->count() }}
        </div>
      @endforeach
    </div>
  </x-card>

  <x-card class="flex flex-col items-start justify-between col-span-6 md:col-span-2 dark:text-gray-50">
    <div class="w-full text-2xl text-left">文章總數</div>
    <div class="w-full font-semibold text-center text-8xl text-sky-500 count-up">{{ $user->posts->count() }}</div>
    <div class="w-full text-2xl text-right">篇</div>
  </x-card>

  <x-card class="flex flex-col items-start justify-between col-span-6 md:col-span-2 dark:text-gray-50">
    <div class="w-full text-2xl text-left">今年寫了</div>
    <div
      class="w-full font-semibold text-center text-8xl text-sky-500 count-up">{{ $user->posts_count_in_this_year }}</div>
    <div class="w-full text-2xl text-right">篇</div>
  </x-card>

  <x-card class="flex flex-col items-start justify-between col-span-6 md:col-span-2 dark:text-gray-50">
    <div class="w-full text-2xl text-left">留言回覆</div>
    <div class="w-full font-semibold text-center text-8xl text-sky-500 count-up">{{ $user->comments->count() }}</div>
    <div class="w-full text-2xl text-right">次</div>
  </x-card>
</div>
