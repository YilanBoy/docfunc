<div class="relative">
    <a
        {{ $attributes->merge(['class' => 'peer h-10 w-10 flex justify-center items-center text-2xl rounded-lg transition duration-150']) }}
    >
        <i class="{{ $icon }}"></i>
    </a>

    <span
        class="absolute left-16 -top-4 flex justify-center items-center w-max opacity-0 transition-all duration-300
        text-gray-900 bg-white rounded-lg ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none
        peer-hover:opacity-100 peer-hover:top-0
        dark:bg-gray-500 dark:text-white"
    >
        {{ $slot }}
    </span>
</div>
