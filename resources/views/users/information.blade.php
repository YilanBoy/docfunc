{{-- 會員基本資訊 --}}
<div class="w-full grid grid-cols-2 gap-6 dark:text-gray-50">
    {{-- 大頭貼 --}}
    <x-card class="col-span-2 md:col-span-1 flex flex-col items-center justify-between">
        <img
            class="h-36 w-36 rounded-full"
            src="{{ $user->gravatar }}"
            alt="{{ $user->name }}"
        >

        {{-- 會員名稱 --}}
        <span class="flex items-center justify-center text-3xl font-semibold mt-2">
            {{ $user->name }}
        </span>
    </x-card>

    {{-- 資訊 --}}
    <x-card class="col-span-2 md:col-span-1 flex flex-col items-start justify-between dark:text-gray-50">
        <span class="text-lg">撰寫文章</span>
        <div class="flex text-xl my-1">
            <span class="text-sky-500 font-semibold">{{ $user->posts->count() }}</span>
            <span class="ml-2 font-semibold">篇</span>
        </div>

        <span class="text-lg">文章留言</span>
        <div class="flex text-xl my-1">
            <span class="text-sky-500 font-semibold">{{ $user->comments->count() }}</span>
            <span class="ml-2 font-semibold">次</span>
        </div>

        <span class="text-xs">
            註冊於 {{ $user->created_at->format('Y / m / d') . '（' . $user->created_at->diffForHumans() . '）' }}
        </span>
    </x-card>

    <x-card class="col-span-2 dark:text-gray-50">
        <h3 class="w-full pb-3 mb-3 text-lg font-semibold border-b-2 border-black dark:border-white">
            <span class="ml-2">個人簡介</span>
        </h3>

        @if ($user->introduction)
            <span class="flex items-center justify-start w-full">{!! nl2br(e($user->introduction)) !!}</span>
        @else
            <span class="flex items-center justify-center w-full">目前尚無個人簡介～</span>
        @endif
    </x-card>

    <x-card class="col-span-2 dark:text-gray-50">
        <h3 class="w-full pb-3 mb-3 text-lg font-semibold border-b-2 border-black dark:border-white">
            <span class="ml-2">文章統計</span>
        </h3>

        <div class="grid grid-cols-12 gap-1">
            @foreach ($categories as $category)
                <div class="col-span-12">
                    {{ $category->name }}
                </div>
                <div class="flex items-center col-span-11">
                    <div
                        class="h-4 transition-all duration-300 rounded-sm growing-bar bg-gradient-to-r from-emerald-400 to-blue-400"
                        style="width: {{ $category->posts->count() ? (int)($category->posts->count() / $user->posts->count() * 100) : 0.2 }}rem;">
                    </div>
                </div>
                <div class="flex items-center justify-end col-span-1 text-lg">
                    {{ $category->posts->count() }} 篇
                </div>
            @endforeach
        </div>
    </x-card>

</div>
