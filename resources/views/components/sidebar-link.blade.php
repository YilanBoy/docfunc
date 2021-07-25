<div class="relative">
    <a
        {{ $attributes->merge(['class' => 'peer flex justify-center items-center text-2xl rounded-md transition duration-150 p-2']) }}
    >
        <i class="{{ $icon }}"></i>
    </a>

    <span
        class="absolute left-20 -top-3 flex justify-center items-center w-max opacity-0 transition-all duration-300
        text-gray-900 bg-white rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none
        peer-hover:opacity-100 peer-hover:top-1
        dark:bg-gray-500 dark:text-white"
    >
        {{ $slot }}
    </span>
</div>
