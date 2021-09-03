<x-card class="w-full flex flex-col justify-center items-center mt-6 md:mt-0 xl:mt-6
dark:text-gray-50">

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
