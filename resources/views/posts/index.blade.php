@section('title', isset($category) ? $category->name : '所有文章')

{{-- 文章列表 --}}
<x-app-layout>
    <div class="container min-h-screen mx-auto mt-6 max-w-7xl">
        <div class="flex flex-col justify-center px-4 space-y-6 xl:space-y-0 xl:flex-row xl:px-0">
            {{-- 文章列表 --}}
            <livewire:posts
                :currentUrl="url()->current()"
                :categoryId="$category->id ?? 0"
                :categoryName="$category->name ?? ''"
                :categoryDescription="$category->description ?? ''"
                :tagId="$tag->id ?? 0"
                :tagName="$tag->name ?? ''"
            />

            {{-- 文章列表側邊欄 --}}
            <div class="w-full space-y-6 xl:w-80">
                {{-- 介紹 --}}
                <x-card class="dark:text-gray-50">
                    <h3 class="pb-3 mb-3 text-lg font-semibold text-center border-b-2 border-black dark:border-white">
                        歡迎來到 <span class="font-mono">{{ config('app.name') }}</span>！
                    </h3>
                    <span>
                        紀錄生活上的大小事
                        <br>
                        此部落格使用 Laravel、Alpine.js 與 Tailwind CSS 開發
                    </span>
                    <div class="flex items-center justify-center mt-7">
                        <a href="{{ route('posts.create') }}"
                           class="relative inline-flex w-64 h-12 border border-emerald-600 rounded-lg group focus:outline-none">
                            <span
                                class="absolute inset-0 inline-flex items-center self-stretch justify-center py-2 font-medium text-center transition-transform bg-emerald-600 rounded-lg text-gray-50 ring-1 ring-green-600 ring-offset-1 ring-offset-green-600 group-hover:-translate-y-2 group-hover:-translate-x-2 group-active:-translate-y-0 group-active:-translate-x-0">
                                <i class="bi bi-pencil-fill"></i><span class="ml-2">新增文章</span>
                            </span>
                        </a>
                    </div>
                </x-card>

                {{-- 熱門標籤 --}}
                @if ($popularTags->count())
                    <x-card class="dark:text-gray-50">
                        <h3 class="pb-3 mb-3 text-lg font-semibold text-center border-b-2 border-black dark:border-white">
                            <i class="bi bi-tags-fill"></i><span class="ml-2">熱門標籤</span>
                        </h3>
                        <div class="flex flex-wrap">
                            @foreach ($popularTags as $popularTag)
                                <x-tag :href="route('tags.show', ['tag' => $popularTag->id])">
                                    {{ $popularTag->name }}
                                </x-tag>
                            @endforeach
                        </div>
                    </x-card>
                @endif

                {{-- 學習資源推薦 --}}
                @if ($links->count())
                    <x-card class="dark:text-gray-50">
                        <h3 class="pb-3 mb-3 text-lg font-semibold text-center border-b-2 border-black dark:border-white">
                            <i class="bi bi-file-earmark-code-fill"></i><span class="ml-2">學習資源推薦</span>
                        </h3>
                        <div class="flex flex-col">
                            @foreach ($links as $link)
                                <a href="{{ $link->link }}" target="_blank" rel="nofollow noopener noreferrer"
                                   class="block p-2 rounded-md hover:bg-gray-200 dark:text-gray-50 dark:hover:bg-gray-600">
                                    {{ $link->title }}
                                </a>
                            @endforeach
                        </div>
                    </x-card>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
