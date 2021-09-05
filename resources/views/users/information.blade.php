<x-card class="w-full mt-6 dark:text-gray-50">

    <h3 class="w-full font-semibold text-lg border-black border-b-2 pb-3 mb-3
    dark:border-white">
        <span class="ml-2">個人簡介</span>
    </h3>

    @if ($user->introduction)
        <span class="w-full flex justify-start items-center">{!! nl2br(e($user->introduction)) !!}</span>
    @else
        <span class="w-full flex justify-center items-center">目前尚無個人簡介～</span>
    @endif
</x-card>

<x-card class="w-full mt-6 dark:text-gray-50">
    <h3 class="w-full font-semibold text-lg border-black border-b-2 pb-3 mb-3
    dark:border-white">
        <span class="ml-2">文章統計</span>
    </h3>

    <div class="grid grid-cols-12 gap-1">
        @foreach ($categories as $category)
            <div class="col-span-12">
                {{ $category->name }}
            </div>
            <div class="col-span-11 flex items-center">
                <div class="h-4 bg-gradient-to-r from-green-400 to-blue-400"
                style="width: {{ $user->posts->count() ? (int)($category->posts->count() / $user->posts->count() * 100) + 0.2 : 0 }}rem;">
                </div>
            </div>
            <div class="col-span-1 flex justify-end items-center text-lg">
                {{ $category->posts->count() }} 篇
            </div>
        @endforeach
    </div>
</x-card>
