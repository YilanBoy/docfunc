<footer id="footer" class="bg-gray-800 pt-4 mt-6">
    <div class="max-w-6xl m-auto text-gray-800 flex flex-wrap justify-left">
        {{-- Col-1 --}}
        <div class="p-5 w-1/2 sm:w-1/3 md:w-1/4">
            {{-- Title --}}
            <div class="text-lg uppercase text-gray-500 font-medium mb-6">
                About
            </div>
            {{-- Links --}}
            <a href="https://github.com/YilanBoy/simple-blog" target="_blank" rel="nofollow noopener noreferrer"
            class="my-3 block text-gray-300 hover:text-gray-500 font-medium duration-300">
                Source Code
            </a>
            <a href="https://github.com/YilanBoy" target="_blank" rel="nofollow noopener noreferrer"
            class="my-3 block text-gray-300 hover:text-gray-500 font-medium duration-300">
                Auther
            </a>
        </div>

        {{-- Col-2 --}}
        <div class="p-5 w-1/2 sm:w-1/3 md:w-1/4">
            {{-- Title --}}
            <div class="text-lg uppercase text-gray-500 font-medium mb-6">
                Learing
            </div>

            {{-- Links --}}
            <a href="https://laracasts.com/" target="_blank" rel="nofollow noopener noreferrer"
            class="my-3 block text-gray-300 hover:text-gray-500 font-medium duration-300">
                Laracasts
            </a>
        </div>

        {{-- Col-3 --}}
        <div class="p-5 w-1/2 sm:w-1/3 md:w-1/4">
            {{-- Title --}}
            <div class="text-lg uppercase text-gray-500 font-medium mb-6">
                Special Thanks
            </div>

            {{-- Links --}}
            <a href="https://laravel.com/" target="_blank" rel="nofollow noopener noreferrer"
            class="my-3 block text-gray-300 hover:text-gray-500 font-medium duration-300">
                Laravel
            </a>
            <a href="https://learnku.com/laravel" target="_blank" rel="nofollow noopener noreferrer"
            class="my-3 block text-gray-300 hover:text-gray-500 font-medium duration-300">
                Laravel China
            </a>
            <a href="https://www.facebook.com/groups/498481680220886" target="_blank" rel="nofollow noopener noreferrer"
            class="my-3 block text-gray-300 hover:text-gray-500 font-medium duration-300">
                Laravel Taiwan
            </a>
            <a href="https://tailwindcss.com/" target="_blank" rel="nofollow noopener noreferrer"
            class="my-3 block text-gray-300 hover:text-gray-500 font-medium duration-300">
                TailwindCSS
            </a>
            <a href="https://alpinejs.dev/" target="_blank" rel="nofollow noopener noreferrer"
            class="my-3 block text-gray-300 hover:text-gray-500 font-medium duration-300">
                Alpine.js
            </a>
            <a href="https://www.flaticon.com/authors/freepik" target="_blank" rel="nofollow noopener noreferrer"
            class="my-3 block text-gray-300 hover:text-gray-500 font-medium duration-300">
                Frepik from flaction
            </a>
        </div>

        {{-- Col-4 --}}
        <div class="p-5 w-1/2 sm:w-1/3 md:w-1/4">
            {{-- Title --}}
            <div class="text-lg uppercase text-gray-500 font-medium mb-6">
                Setting
            </div>

            {{-- Links --}}
            <a href="{{ route('setting.theme') }}"
            class="my-3 block text-gray-300 hover:text-gray-500 font-medium duration-300">
                Theme
            </a>
        </div>
    </div>

    {{-- Copyright Bar --}}
    <div class="pt-2">
        <div class="flex flex-col md:flex-row max-w-6xl pb-5 px-3 m-auto pt-5
        border-t border-gray-500">
            <div class="flex justify-center items-center mb-2 md:mb-0 text-sm text-gray-400">
                © Copyright 2020-{{ date('Y') }}. All Rights Reserved.
            </div>

            <div class="flex flex-row justify-center md:flex-auto md:justify-end space-x-4">
                <a href="https://github.com/YilanBoy" target="_blank" rel="nofollow noopener noreferrer"
                class="text-2xl text-gray-300 hover:text-gray-500 duration-300">
                    <i class="bi bi-github"></i>
                </a>
                <a href="https://twitter.com/bVK1uFaMvQkDyPR" target="_blank" rel="nofollow noopener noreferrer"
                class="text-2xl text-gray-300 hover:text-gray-500 duration-300">
                    <i class="bi bi-twitter"></i>
                </a>
                <a href="https://www.facebook.com/profile.php?id=100004204543711" target="_blank" rel="nofollow noopener noreferrer"
                class="text-2xl text-gray-300 hover:text-gray-500 duration-300">
                    <i class="bi bi-facebook"></i>
                </a>
            </div>
        </div>
    </div>
</footer>
